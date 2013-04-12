<?php sfMediaBrowserUtils::loadAssets(sfConfig::get('app_sf_media_browser_assets_list')) ?>

<fieldset>
  <legend>GALERIA</legend>

  <div id="sf_media_browser_user_message"></div>

  <!--<div id="sf_media_browser_forms">
  <?php //include_partial('sfMediaDoctrine/create_asset', array('upload_form' => $upload_form, 'object' => $object)); ?>

  <?php //include_partial('sfMediaDoctrine/create_album', array('album_form' => $album_form)); ?>

    <div class="clear"></div>
  </div>-->

  <?php if (sfConfig::get('app_sf_media_browser_albums_enabled')): ?>
    <h2><?php echo sprintf(__('Current directory : %s'), $mdAssetAlbum->getName()); ?></h2>
  <?php endif; ?>

  <div class="md-raw-top">
    <div class="left">
      <p><?php echo $files->count() . ' archivos'; ?></p>
    </div>    
    <div class="rigth">
      <?php if (sfConfig::get('app_sf_media_browser_vimeo_enabled')): ?>
        <input type="button" value="Vimeo" />
      <?php endif; ?>

      <?php if (sfConfig::get('app_sf_media_browser_youtube_enabled')): ?>      
        <input type="button" value="Youtube" />
      <?php endif; ?>

      <?php if (sfConfig::get('app_sf_media_browser_issuu_enabled')): ?>      
        <input type="button" value="Issuu" />
      <?php endif; ?>      
      <a id="sf_open_uploader" class="iframe" href="<?php echo url_for('sfMediaUploader/uploader?object_id=' . $object->getId() . '&object_class=' . $object->getObjectClass()); ?>">Subir archivos</a>
      <!-- <input type="button" value="Media" /> -->
    </div>
    <div class="clear"></div>
  </div>

  <div id="sf_media_files">
    <?php include_partial('sfMediaDoctrine/list_files', array('files' => $files, 'mdAssetAlbum' => $mdAssetAlbum)); ?>
  </div>
    
  <?php include_partial('sfMediaDoctrine/list_albums', array('dirs' => $dirs)); ?>    
</fieldset>

<script type="text/javascript">
  $(document).ready(function(){
    $('#sf_open_uploader').fancybox({ autoScale: true });
  });
</script>
