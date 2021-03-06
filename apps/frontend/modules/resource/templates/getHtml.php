<?php use_helper('Text') ?>
<?php use_javascript('jquery.oembed.js') ?>
<?php use_javascript('jquery.masonry.js') ?>
<?php use_javascript('gallery.behaviors.js') ?>
<?php $hiddenParameters = array('action', 'sf_format', 'collection', 'segment', 'module', 'viewmode') ?>
<?php $inSearch = array_keys($sf_request->getParameterHolder()->getAll()) ?>

<style>
.item {width:430px;margin:10px;float:left;}
#resources img {width:420px;}
</style>

<h2>Collection : <?php echo $sf_request->getParameter('collection') ?> / Segment : <?php echo $sf_request->getParameter('segment') ?></h2>

<div class="results html">
	<p style="text-align: center;">Vous consultez les résultats <strong><?php echo $sf_request->getParameter('start', 0) + 1 ?></strong> à <strong><?php echo ($sf_request->getParameter('start', 0) + 50 > $results['num_found'] ? $results['num_found'] : $sf_request->getParameter('start', 0) + 50) ?></strong> parmi les  <strong><?php echo $results['num_found']?></strong> trouvés</p>

	<?php include_partial('pagination', array('pagination' => $pagination, 'results' => $results)) ?>

	<hr />
	
	<dl>
		<dt>Critères de recherche (<a href="<?php echo url_for(sprintf('@resources_collection?collection=%s#segments-%s', $sf_request->getParameter('collection'), $sf_request->getParameter('segment'))) ?>" title="Consulter la documentation du segment <?php echo $sf_request->getParameter('segment') ?>">?</a>)</dt>
		<?php $parameters = $sf_request->getParameterHolder()->getAll() ?>
		<?php foreach (array_keys($sf_request->getParameterHolder()->getAll()) as $paramName => $paramValue): ?>
			<?php if (!in_array($paramValue, $hiddenParameters)): ?>
				<dd class="<?php in_array($paramValue, $inSearch) ? 'in_search' : '' ?>"><strong><?php echo $paramValue ?> :</strong> <?php echo $parameters[$paramValue] ?></dd>
			<?php endif; ?>
		<?php endforeach; ?>
	</dl>
	<dl>
		<strong>Autres formats :</strong>
		<?php foreach ($urlsFormats as $format => $urlFormat): ?>
		<a href="<?php echo $urlFormat ?>" title="Obtenir les mêmes résultats au format <?php echo ucfirst($format) ?> format"><?php echo ucfirst($format) ?></a>
		(<a href="<?php echo $sf_request->getRelativeUrlRoot() ?>/collections/links#formats-<?php echo $format ?>" x-fragment="#formats-<?php echo $format ?>" title="Consulter la documentation du format" class="help">?</a>)
		<?php endforeach; ?>
		<p id="help"></p>
	</dl>
	<dl>
		<strong>Mode de visualisation :</strong>
		<?php if ($sf_request->getParameter('viewmode', 'raw') == 'raw'): ?>
			raw / <a href="<?php echo $viewModes['gallery'] ?>">gallery</a>
		<?php elseif ($sf_request->getParameter('viewmode', 'raw') == 'gallery'): ?>
			<a href="<?php echo $viewModes['raw'] ?>">raw</a> / gallery
		<?php endif; ?>
	</dl>
	<hr />
	
	<?php if (count($results) > 1): ?>
		<div id="resources">
			<?php foreach ($results as $resource): ?>
				<?php if (is_array($resource)): ?>
					<?php if ($sf_request->getParameter('viewmode', 'raw') == 'raw'): ?>
					  <dl>
					  	<dt><a href="<?php echo $resource['url'] ?>" title="Accéder à la ressource" target="_blank"><?php echo $resource['url'] ?></a></dt>
					  	<?php foreach ($resource as $propName => $propValue): ?>
					  	<dd><strong><?php echo $propName ?> :</strong> <a href="<?php echo url_for(sprintf('@resources_collection_segment_get?collection=%s&segment=%s', $collection, $segment)) ?>?<?php echo urlencode($propName) ?>=<?php echo urlencode(utf8_decode($propValue)) ?>"><?php echo utf8_decode($propValue)?> </a></dd>
					  	<?php endforeach; ?>
					  </dl>
					<?php elseif ($sf_request->getParameter('viewmode', 'raw') == 'gallery'): ?>
						<div class="item">
							<a class="oembed" href="<?php echo $resource['url'] ?>" title="Accéder à la ressource" target="_blank"><?php echo $resource['url'] ?></a>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>

		<hr style="clear:both;" />

		<?php include_partial('pagination', array('pagination' => $pagination, 'results' => $results)) ?>

	<?php else: ?>
		<p style="text-align: center;">No results. DARN !</p>
	<?php endif; ?>
</div>