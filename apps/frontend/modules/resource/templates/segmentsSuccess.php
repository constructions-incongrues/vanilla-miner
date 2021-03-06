<?php
use_javascript('http://shjs.sourceforge.net/sh_main.min.js');
use_javascript('http://shjs.sourceforge.net/lang/sh_javascript.min.js');
use_javascript('http://shjs.sourceforge.net/lang/sh_xml.min.js');
use_javascript('http://shjs.sourceforge.net/lang/sh_php.min.js');
use_stylesheet('http://shjs.sourceforge.net/css/sh_acid.min.css');
?>
<div id="container" class="large">
<h2>Documentation de la collection "<?php echo $sf_request->getParameter('collection') ?>"</h2>
<div id="documentation-container">
  <div id="toc">
    <!-- Holds dynamically generated (JavaScript) table of contents -->
  </div>

  <h2 id="presentation">Présentation de la collection</h2>
  <?php include_partial(sprintf('resource/documentation/%s/description', $sf_request->getParameter('collection'))) ?>

  <h2 id="howto">Comment constituer une requête ?</h2>
  <?php include_partial('resource/documentation/howto') ?>

  <h2 id="examples">Exemples</h2>
  <?php include_partial(sprintf('resource/documentation/%s/cookbook', $sf_request->getParameter('collection'))); ?>

  <h2 id="reference">Référence</h2>
  <h3 id="schema">Schéma</h3>
  <?php include_partial(sprintf('resource/documentation/%s/schema', $sf_request->getParameter('collection'))) ?>

  <h3 id="common-parameters">Paramètres transverses</h3>
  <?php include_partial('resource/documentation/common_parameters') ?>

  <h3 id="formats">Formats</h3>
  <?php include_partial('resource/documentation/formats') ?>

  <h3 id="segments">Segments</h3>
  <?php foreach ($segments as $segment): ?>
    <h4 id="segments-<?php echo $segment?>"><?php echo $segment ?></h4>
    <?php include_partial(sprintf('resource/documentation/%s/segment/%s', $sf_request->getParameter('collection'), $segment)); ?>
    <p>URL de requête :</p>
    <pre>GET <a href="<?php echo url_for(sprintf('@resources_collection_segment_get?collection=%s&segment=%s', $sf_request->getParameter('collection'), $segment), true) ?>" ><?php echo url_for(sprintf('@resources_collection_segment_get?collection=%s&segment=%s', $sf_request->getParameter('collection'), $segment), true) ?></a></pre>
  <?php endforeach; ?>
</div>
</div>