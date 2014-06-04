<div id="multiupload_view">
 
    <div id="selectview">

        <!-- <p><a id="add" class="addbutton hidden" href="#">Choose Files</a></p> -->
        <?php echo $form['filename'] ?>

        <!-- <div class="uploadtype"><?php //echo html_entity_decode(str_replace(array('{a}','{/a}'), array('<a href="#basic" id="singleupload" onclick="basicUpload();">','</a>'), __('mdMediaManager_text_basicUpload')));?><!-- Gets filled by upload.js --><!-- </div> -->
    </div>

    <div id="fileblock">
        <div id="filelist">
            <table class="table table-hover" id="files">
                <thead>
                    <tr>
                        <td width="260">Archivo<?php //echo __('mdMediaManager_text_file');?></td>
                        <td width="60">Tamaño<?php //echo __('mdMediaManager_text_size');?></td>
                        <?php if(false): ?>
                        <td width="100">Album<?php //echo __('mdMediaManager_text_album');?></td>
                        <?php endif; ?>
                        <td width="60">Acciones<?php //echo __('mdMediaManager_text_status');?></td>
                    </tr>
                </thead>
                <tbody>
                <!-- Gets filled with file items -->
                </tbody>
            </table>
        </div>

        <p id="statsrow">
<!--             <div id="status">
                <div id="progressbar" style="width: 0%"></div>
            </div> -->
            <div id="status" class="progress">
              <div class="progresstext">
                  <span id="progressinfo"></span>&nbsp;<a id="abortupload" href="#action"></a>
              </div>
              <div id="progressbar" class="progress-bar progress-bar-success progress-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                <span class="sr-only">40% Complete (success)</span>
              </div>
            </div>
            <a class="btn btn-success pull-left" id="addmore" href="#action"><?php echo "Agregar más";//__('mdMediaManager_text_addMore');?></a>
            <a class="btn btn-success pull-right" id="uploadstart" href="#action">Subir archivos</a>
        </p>
    </div>

    <input class="text" id="rename" tipo="text" type="text" value="" style="display:none" />

    <?php $title = ""; ?>
    <?php if(false): ?>
    <?php $albums = $manager->getAlbums(); ?>
    <select id="change" tipo="select" style="display:none">
        <?php foreach($albums as $album): ?>
        <?php if($album->id == $album_id): $title = $album->title; endif; ?>
        <option value="<?php echo $album->id; ?>"><?php echo $album->title; ?></option>
        <?php endforeach; ?>
    </select>
    <?php endif; ?>
</div>

<script type="text/javascript">
var __MD_UPLOAD_FILE_TEMPLATE = "<tr id=\"{id}\">" +
        "<td class=\"fname\"><a href=\"#action\" class=\"rename\">{name}</a></td>" +
        "<td class=\"size\">{sizefix}</td>" +
        <?php if(false): ?>
        "<td class=\"change\"><a href=\"#action\" class=\"change\">{album}</a><input class=\"album_selected\" type=\"hidden\" value=\"<?php echo $album_id; ?>\"></td>" +
        <?php endif; ?>
        "<td class=\"status\"><a href=\"#action\" class=\"remove\"><?php echo 'Quitar'//__("mdMediaManager_text_remove"); ?></a>" +
    "</tr>";
var __MD_ALBUM_SELECTED     = "<?php echo $title; ?>";
var __MD_ALBUM_SELECTED_ID  = "<?php echo $album_id; ?>";
</script>



