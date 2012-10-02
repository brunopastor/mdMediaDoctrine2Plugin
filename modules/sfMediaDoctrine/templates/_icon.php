<?php use_helper('Text'); ?>

<div class="icon">
  <?php echo link_to(image_tag($file->getIcon()), $file->getUrl(NULL, NULL, true), array('target' => '_blank')) ?>
</div>

<label class="name"><?php echo truncate_text($file->getFilename(), 17) ?></label>

<div class="action">
  <span class="size"><?php echo $file->getSize() ?></span>
  <?php if($file->isImage()): ?>
  <span class="dimensions"><?php echo $file->getWidth() ?>x<?php echo $file->getHeight() ?></span>
  <?php endif ?>
  <?php echo link_to('delete', 'sf_media_doctrine_file_delete', array('file' => $file->getId()),
                array('class' => 'delete', 'title' => sprintf('Delete file "%s"', $file->getFilename()))
            ) ?>
</div>
