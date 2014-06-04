<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<div class="dialog">

  <div id="facts" class="facts">
    <span class="valid-files">Archivos Validos: *.jpg;*.jpeg;*.gif;*.png</span>
  </div>

  <div id="content">

    <div id="uploadarea">

      <?php //include_partial('sfMediaUploader/swf_single', array('manager' => $manager, 'album_id' => $album_id)); ?>

      <?php include_partial('sfMediaUploader/swf_multiple', array('form' => $form, 'album_id' => $album_id)); ?>

      <script type="text/javascript">
        var __MD_OBJECT_ID = <?php echo $object->getId(); ?>;
        var __MD_OBJECT_CLASS = "<?php echo $object->getObjectClass(); ?>";
      </script>

    </div>

  </div>
</div>

<div id="upload_container_overlay" class="upload_container" style="display:none"></div>

<div id="upload_container" class="upload_progress" style="display:none">
  <div class="progressWindow">Cargando...</div>
</div>
