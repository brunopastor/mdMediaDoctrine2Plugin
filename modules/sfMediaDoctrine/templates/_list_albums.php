<ul id="sf_media_browser_albums">
<?php if(true): ?>  
  <?php foreach($dirs as $dir): ?>
    <li class="folder">
      <a class="update_files_list" href="javascript:void();" data-object-id="<?php echo $object->getId() ?>" data-object-class="<?php echo $object->getObjectClass() ?>" data-md-asset-album-id="<?php echo $dir->getId() ?>">
      <div class="icon">
        <img src="/mdMediaDoctrine2Plugin/images/icons/folder.png" />
      </div>
      <label class="name"><?php echo $dir ?></label>
      <div class="action"><?php echo link_to('delete', 'sf_media_doctrine_dir_delete', array('sf_method' => 'delete', 'directory' => urlencode(''.'/'.$dir)), array('class' => 'delete', 'title' => sprintf(__('Delete folder "%s"'), $dir))) ?></div>
    </li>
  <?php endforeach ?>
<?php endif; ?>

</ul>
<script type="text/javascript">
$('a.update_files_list').click(function(){
  $this = $(this);
  parent.mdMediaDoctrine.getInstance().updateAssetList(
    $this.attr('data-object-id'), 
    $this.attr('data-object-class'),
    $this.attr('data-md-asset-album-id'));
});
</script>