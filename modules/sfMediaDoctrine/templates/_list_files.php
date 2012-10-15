<div id="droppable" class="ui-widget-header">
  <?php if(!$mdAssetAlbum->hasAvatar()): ?>
    <a href="<?php echo url_for('@sf_media_doctrine_change_avatar') . '?md_asset_album_id=' . $mdAssetAlbum->getId(); ?>">
      <img src="/mdMediaDoctrine2Plugin/images/no_image.jpg" avatar="/mdMediaDoctrine2Plugin/images/no_image.jpg" alt="avatar" width="220" height="272" />
    </a>
  <?php else: ?>
    <a href="<?php echo url_for('@sf_media_doctrine_change_avatar') . '?md_asset_album_id=' . $mdAssetAlbum->getId(); ?>">
      <img src="/<?php echo $mdAssetAlbum->getUrl(220, 272); ?>" avatar="/mdMediaDoctrine2Plugin/images/no_image.jpg" alt="avatar" width="220" />
    </a>
  <?php endif; ?>
</div>

<ul id="sf_media_browser_list">

  <?php foreach ($files as $file): ?>
    <li id="file_<?php echo $file->getId(); ?>" class="file">

      <?php include_partial('sfMediaDoctrine/icon', array('file' => $file)); ?>

    </li>
  <?php endforeach ?>

</ul>
