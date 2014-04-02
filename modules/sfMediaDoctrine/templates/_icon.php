<?php use_helper('Text'); ?>
  <a class="toolbar-opener" href="" id="vertical-toolbar-<?php echo $file->getId() ?>"><span class="glyphicon glyphicon-cog"></span></a>
  <div class="user-toolbar-options" id="user-toolbar-options-<?php echo $file->getId() ?>">
	<a rel="group" class="btn-info fancybox view-file" href="<?php echo $file->getUrl(64, NULL, 'original'); ?>" alt="<?php echo $file->getFilename(); ?>"><span class="glyphicon glyphicon-zoom-in"></span></a>
	<a rel="group" class="btn-info fancybox view-file-info" href="<?php echo url_for('@file_info?id='.$file->getId()) ?>"><span class="glyphicon glyphicon-list"></span></a>
	<a class="btn-info delete-file" 
	   title="Delete: <?php echo $file->getFilename(); ?>" 
	   href="<?php echo url_for('@sf_media_doctrine_file_delete?file='.$file->getId()) ?>">
		<span class="glyphicon glyphicon-trash"></span>		
	</a>
  </div>
  <script>
	$(document).ready(function() {
		$('#vertical-toolbar-<?php echo $file->getId() ?>').toolbar({
			content: '#user-toolbar-options-<?php echo $file->getId() ?>', 
			position: 'top',
			hideOnClick: true
		});
	});
  </script>
<div class="icon">
	<img src="<?php echo $file->getUrl(64, NULL, 'original'); ?>" alt="<?php echo $file->getFilename(); ?>">
	<?php //echo link_to(image_tag($file->getIcon()), $file->getUrl(NULL, NULL, 'original'), array('target' => '_blank')) ?>
</div>

<!-- <label class="name"><?php //echo truncate_text($file->getFilename(), 17) ?></label> -->

<div class="action">
  <!-- <span class="size"><?php //echo $file->getSize() ?></span> -->
  <?php //if($file->isImage()): ?>
  <!-- <span class="dimensions"><?php //echo $file->getWidth() ?>x<?php //echo $file->getHeight() ?></span> -->
  <?php //endif ?>
  <?php //echo link_to('delete', 'sf_media_doctrine_file_delete', array('file' => $file->getId()),
				//array('class' => 'delete', 'title' => sprintf('Delete file "%s"', $file->getFilename()))
			//) ?>
</div>

<script>
	$(document).ready(function() {
		$('.delete-file').click(function(e){
			e.preventDefault();

			self = $(this);
			action = $(this).attr('href');
			title = 'Eliminar Archivo'
			content = 'Seguro que quieres borrar este archivo?';

			$('.alert-dialog').dialog({
				modal: true,
				resizable: false,
				title: title,
				hide: 'drop',
				show: 'drop',
				buttons: [
					{ 
						text: "OK",
						class: 'dialog-continue btn btn-success',
						click: function() {
							showLoading();
							$.ajax({
							  type: "POST",
							  url: action,
							  dataType: "json",
							  success: function(data){
								// delete file from list
								self.parents('.file').fadeOut();
							  
								// update avatar if it has been deleted
								if(data.options.is_avatar){
								  $('#droppable').find('img').attr('src', $('#droppable').find('img').attr('avatar'));
								}
								$('.dialog-cancel').trigger('click');
								hideLoading();
							  }
							});
						}
					},
					{
						text: "Cancelar",
						class: 'dialog-cancel btn btn-danger',
						click: function() { 
						$(this).dialog('close'); 
					}
				}]
			});
		});
		$('.view-file').fancybox();
		$('.view-file-info').fancybox({
			type : 'iframe',
			width : 468,
		});
	});
</script>
