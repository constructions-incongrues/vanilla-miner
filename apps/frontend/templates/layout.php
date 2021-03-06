<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" prefix="og: http://ogp.me/ns#">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" type="image/png" href="<?php echo $sf_request->getRelativeUrlRoot() ?>/images/favicon.png" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
    <meta name="description" content="Ce site est dédié à l'exploration et à l'exploitation des ressources postées jour après jour par les contributeurs du forum des Musiques Incongrues." />
    <meta property="og:image" content="http://www.musiques-incongrues.net/forum/extensions/Vanillacons/smilies/otro%20-%20fruits/pin01.gif" />
  </head>
  <body>
  
<?php if (function_exists('newrelic_get_browser_timing_header')): ?>
	<?php echo newrelic_get_browser_timing_header(); ?>
<?php endif; ?>

    <h1><?php echo link_to('data.musiques-incongrues.net', '@homepage', array('title' => "Retourner à la page d'accueil")) ?></h1>

    <img id="ananas" src="http://www.musiques-incongrues.net/forum/extensions/Vanillacons/smilies/otro%20-%20fruits/pin01.gif" title="ANANAS !" alt="Ananas"/>

   <p id="url"></p>

    <?php echo $sf_content ?>

    <hr />

    <p><a href="https://github.com/contructions-incongrues/vanilla-miner/tree/<?php echo sfConfig::get('app_version') ?>">Vanilla Miner <?php echo sfConfig::get('app_version') ?></a> est développé par <a href="http://www.constructions-incongrues.net/">Constructions Incongrues</a> et est hébergé par <a href="http://www.pastis-hosting.net">Pastis Hosting</a>.</p>
    <p>Le code source du service est <a href="https://launchpad.net/vanilla-miner">distribué</a> sous licence <a href="http://www.gnu.org/licenses/agpl.html">GNU Affero GPLv3</a>.</p>
    <p>Ce service utilise (notamment) <a href="http://www.symfony-project.org">symfony</a>, <a href="http://www.doctrine-project.org">Doctrine</a> et <a href="http://lucene.apache.org/solr/">Solr</a>.</p>
    <p>Contact : contact @ musiques-incongrues . net</p>

    <script type="text/javascript">

      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-16967840-1']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();

    </script>

<?php if (function_exists('newrelic_get_browser_timing_footer')): ?>
	<?php echo newrelic_get_browser_timing_footer(); ?>
<?php endif; ?>

  </body>
</html>
