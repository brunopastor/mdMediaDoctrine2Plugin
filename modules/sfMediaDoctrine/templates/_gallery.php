<?php sfMediaBrowserUtils::loadAssets(sfConfig::get('app_sf_media_browser_assets_list')) ?>

<!-- <fieldset>
  <legend>Galería</legend>

  <div id="sf_media_browser_user_message"></div>

  <div id="sf_media_browser_forms">
  <?php //include_partial('sfMediaDoctrine/create_asset', array('upload_form' => $upload_form, 'object' => $object)); ?>

  <?php //include_partial('sfMediaDoctrine/create_album', array('album_form' => $album_form)); ?>

    <div class="clear"></div>
  </div> -->

  <?php if (sfConfig::get('app_sf_media_browser_albums_enabled')): ?>
    <?php include_partial('sfMediaDoctrine/list_albums', array('dirs' => $dirs, 'object'=>$object, 'mdAssetAlbum'=>$mdAssetAlbum)); ?>    
  <?php endif; ?>
  
  <div class="clear"></div>
  <div id="sf_media_files">
    <?php include_partial('sfMediaDoctrine/list_files', array('object'=>$object, 'files' => $files, 'mdAssetAlbum' => $mdAssetAlbum)); ?>
  </div>
    
<!-- </fieldset> -->