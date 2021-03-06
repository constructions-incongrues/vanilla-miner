<?php

/**
 * Symfony task for extracting urls using an extraction drivers.
 * Extracted urls will be added to miner's instance links collection.
 */
class minerExtractlinksTask extends sfBaseTask
{
    /**
     * Ongoing extraction's log entry.
     *
     * @var ExtractionLog
     */
    private $log_entry;

    /**
     * Configures task.
     */
    protected function configure()
    {
        $this->addArguments(array(
        new sfCommandArgument('dsn', sfCommandArgument::REQUIRED),
        ));

        // TODO add a --verbose switch
        $this->addOptions(array(
        new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
        new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
        new sfCommandOption('extraction-driver', null, sfCommandOption::PARAMETER_REQUIRED, 'Extraction driver class name', 'CI_Extractor_LussumoVanilla1'),
        new sfCommandOption('incremental', null, sfCommandOption::PARAMETER_REQUIRED, 'If true, only extracts URLs from new and updated resources since last extraction', true),
        new sfCommandOption('progress', null, sfCommandOption::PARAMETER_NONE, 'Displays a progress bar'),
        new sfCommandOption('and-expand', null, sfCommandOption::PARAMETER_NONE, 'Launches expansion on newly extracted links'),
        ));

        $this->namespace        = 'miner';
        $this->name             = 'extract-links';
        $this->briefDescription = 'Extracts links from datasource';
        $this->detailedDescription = <<<EOF
Call it with:

  [php symfony miner:extract-links --extraction-driver=My_Extraction_Driver|INFO]
EOF;
    }

    /**
     * Executes task.
     *
     * @param array $arguments
     * @param array $options
     */
    protected function execute($arguments = array(), $options = array())
    {
        // Setup logging
        $this->dispatcher->connect('log', array($this, 'onLog'));

        // Setup signal handling
        declare(ticks = 1);
        pcntl_signal(SIGTERM, array($this, 'handleSignal'));
        pcntl_signal(SIGINT, array($this, 'handleSignal'));

        // TODO : autoload classes
        $driver_classname_parts = explode('_', $options['extraction-driver']);
        require sprintf('%s/vendor/CI/Extractor.php', sfConfig::get('sf_lib_dir'));
        require sprintf('%s/vendor/CI/Extractor/%s.php', sfConfig::get('sf_lib_dir'), array_pop($driver_classname_parts));

        // Sanity checks
        if (!class_exists($options['extraction-driver']))
        {
            throw new InvalidArgumentException(sprintf('Class "%s" does not exist', $options['extraction-driver']));
        }

        if ($options['incremental'] === 'false')
        {
            $options['incremental'] = false;
        }

        // Instanciate database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        // If extraction is incremental, retrieve last extraction date
        $since = null;
        if ($options['incremental'])
        {
            $since = $this->getLastExtractionDate($connection);
            if ($since)
            {
                $this->logSection('extract', sprintf('Incrementally extracting URLs from resources created or updated since "%s"', $since));
            }
            else
            {
                $this->logSection('extract', 'Found no entries in extraction log. Extracting URLs from all resources.');
            }
        }

        /*
         * Create new extraction log entry.
         *
         * This log entry is stored in an object's field  in order to make it available
         * to the handleSignal() method.
         */
        $this->log_entry = new ExtractionLog();
        $this->log_entry->extraction_driver = $options['extraction-driver'];
        $this->log_entry->started_on = date('Y-m-d H:i:s');
        $this->log_entry->save();

        // Instanciate and configure extraction engine
        $extractor = new $options['extraction-driver']($this->dispatcher, $this->configuration, $since);

        // Extraction statistics
        $urls_found_count = 0;
        $resources_total = $extractor->countResources($arguments['dsn'], $since);

        if ($resources_total > 0)
        {
            // Instanciate an configure progress bar
            if ($options['progress'])
            {
                include 'Console/ProgressBar.php';
                $progress_bar = new Console_ProgressBar(
          '** '.$arguments['dsn'].' %fraction% resources [%bar%] %percent% | ',
          '=>', '-', 80, $resources_total, array('ansi_terminal' => true)
                );
            }

            // Extract resources from source and insert them in Links database
            while ($resource_extraction_info = $extractor->extract($arguments['dsn'], $options['connection'], $since))
            {
                // Update extraction statistics
                $urls_found_count += $resource_extraction_info['urls_found_count'];

                // Update extraction log
                $this->log_entry->resources_parsed = $resource_extraction_info['resources_parsed_count'];
                $this->log_entry->urls_extracted = $urls_found_count;
                $this->log_entry->save();

                // Update progress bar
                if ($options['progress'])
                {
                    $progress_bar->update($resource_extraction_info['resources_parsed_count']);
                }
            }

            // Log
            $this->logSection('extract', sprintf('%d URLs were extracted from %d resources', $urls_found_count, $resources_total));
        }
        else
        {
            $this->logSection('extract', 'No resources to extract. Exiting.');
        }

        // Run links expansion if user asked so
        if ($options['and-expand'])
        {
            $this->runTask('miner:expand-links', array(), array('progress' => $options['progress']));
        }

        // Record finish time and statistics
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $this->log_entry->finished_on = date('Y-m-d H:i:s');
        $this->log_entry->save();
    }

    /**
     * Catches interruption signals.
     * If script is interrupted, ongoing extraction's log entry is deleted.
     *
     * @param $signal
     */
    public function handleSignal($signal)
    {
        // Delete current log entry
        $this->log_entry->delete();

        // Exit script
        exit;
    }

    /**
     * Returns date of most recent extraction.
     *
     * @param string $doctrine_connection
     */
    private function getLastExtractionDate()
    {
        // Retrieve last extraction date
        $last_extraction_date = Doctrine_Query::create()
        ->select('l.started_on')
        ->from('ExtractionLog l')
        ->orderBy('l.started_on desc')
        ->limit(1)
        ->execute(null, Doctrine_Core::HYDRATE_SINGLE_SCALAR);

        if (!$last_extraction_date)
        {
            $last_extraction_date = null;
        }

        return $last_extraction_date;
    }

    /**
     * Listens for "log" events and logs messages to stdout.
     *
     * @param sfEvent $event
     */
    public function onLog(sfEvent $event)
    {
        $this->logSection('extract', $event['message']);
    }
}
