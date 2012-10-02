<?php
/*
 * Recibe el objecto y el formulario: $object y $upload_form
 */
?>
<fieldset id="sf_media_browser_upload">

  <legend><?php echo __('Upload a file') ?></legend>

  <form action="<?php echo url_for('sf_media_doctrine_file_create') ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $object->getObjectClass(); ?>" name="object_class" />
    <input type="hidden" value="<?php echo $object->getObjectId(); ?>" name="object_id" />
    <?php echo $upload_form ?>

    <input type="submit" class="submit" value="<?php echo __('Save') ?>" />

  </form>

</fieldset>