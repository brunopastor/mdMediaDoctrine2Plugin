<ul id="sf_media_browser_albums">

<?php if(false): ?>  
  <?php foreach($dirs as $dir): ?>
    <li class="folder">
      <div class="icon">
        <img src="/sfMediaBrowserPlugin/images/icons/folder.png" />
        <?php //echo link_to(image_tag('/sfMediaBrowserPlugin/images/icons/folder.png'), '', array_merge($sf_data->getRaw('current_params'), array('dir' => ''.'/'.$dir)), array('title' => $dir)) ?>
      </div>
      <label class="name"><?php echo $dir ?></label>
      <div class="action"><?php //echo link_to('delete', 'sf_media_doctrine_dir_delete', array('sf_method' => 'delete', 'directory' => urlencode(''.'/'.$dir)), array('class' => 'delete', 'title' => sprintf(__('Delete folder "%s"'), $dir))) ?></div>
    </li>
  <?php endforeach ?>
<?php endif; ?>

</ul>
