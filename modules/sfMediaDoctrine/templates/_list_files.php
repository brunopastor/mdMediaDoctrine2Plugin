<?php use_helper('mdMedia'); ?>
  <fieldset>
    <legend>Galerias</legend>
    <div class="md-raw-top">
      <!-- <h2><?php //echo sprintf(__('Current directory : %s'), $mdAssetAlbum->getName()); ?></h2> -->   
      <div class="rigth">
        
        <!-- <input type="button" value="Media" /> -->

      </div>
      <div class="clear"></div>
    </div>
  
    <div id="droppable" class="ui-widget-header">
      <?php if(!$mdAssetAlbum->hasAvatar()): ?>
          <img action="<?php echo url_for('@sf_media_doctrine_change_avatar') . '?md_asset_album_id=' . $mdAssetAlbum->getId(); ?>" src="/mdMediaDoctrine2Plugin/images/no_image.jpg" avatar="/mdMediaDoctrine2Plugin/images/no_image.jpg" alt="avatar" width="220" height="272" />
      <?php else: ?>
          <img action="<?php echo url_for('@sf_media_doctrine_change_avatar') . '?md_asset_album_id=' . $mdAssetAlbum->getId(); ?>" src="<?php echo get_url($mdAssetAlbum->getAvatarFilename(), 220, 272, 'resizecrop'); ?>" avatar="/mdMediaDoctrine2Plugin/images/no_image.jpg" alt="avatar" />
      <?php endif; ?>
    </div>

    <ul id="sf_media_browser_list">
      
      <?php foreach ($files as $file): ?>
        <li id="file_<?php echo $file->getId(); ?>" class="file">

          <?php include_partial('sfMediaDoctrine/icon', array('file' => $file)); ?>

        </li>
      <?php endforeach ?>
      <?php if ($yt = $mdAssetAlbum->getYoutube()): ?> 
        <li>
          <iframe width="320" height="240"
          src="<?php echo '//www.youtube.com/embed/'.$yt->getPath() ?>">
          </iframe>
        </li>
      <?php endif; ?>
    </ul>
    <div class="clear"></div>
  </fieldset>
  <a id="sf_open_uploader" class="sf_open_new_content iframe btn btn-success pull-left" href="<?php echo url_for('sfMediaUploader/uploader?object_id=' . $object->getId() . '&object_class=' . $object->getObjectClass() . '&md_asset_album_id=' . $mdAssetAlbum->getId()); ?>">Agregar Imagenes</a>
  <?php if (sfConfig::get('app_sf_media_browser_vimeo_enabled')): ?>
    <input type="button" value="Vimeo" />
  <?php endif; ?>

  <?php if (sfConfig::get('app_sf_media_browser_youtube_enabled')): ?>      
    <a id="sf_open_embebed" class="sf_open_new_content iframe btn btn-success pull-left" href="<?php echo url_for('sfMediaUploader/UploaderEmbebidos?object_id=' . $object->getId() . '&object_class=' . $object->getObjectClass() . '&md_asset_album_id=' . $mdAssetAlbum->getId()); ?>">Relacionar Youtube</a>
  <?php endif; ?>

  <?php if (sfConfig::get('app_sf_media_browser_issuu_enabled')): ?>      
    <input type="button" value="Issuu" />
  <?php endif; ?>      
  <div class="pull-right">
    <p><?php echo $files->count() . ' archivos'; ?></p>
  </div> 
  <script type="text/javascript">
      $('.sf_open_new_content').fancybox({ 
        fitToView : true,
        width: '580px',
        height : '80%',
        autoSize : false,
        type : 'iframe'
      });
  </script>
