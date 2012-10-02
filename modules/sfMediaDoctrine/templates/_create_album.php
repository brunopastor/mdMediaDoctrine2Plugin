<?php
/*
 * Recibe el formulario $album_form
 */
?>
<fieldset id="sf_media_browser_mkdir">
  <legend><?php echo __('Create a new directory') ?></legend>

  <form action="<?php echo url_for('sf_media_doctrine_dir_create') ?>" method="post">

    <?php echo $album_form; ?>

    <input type="submit" class="submit" value="<?php echo __('Create') ?>" />

  </form>

</fieldset>