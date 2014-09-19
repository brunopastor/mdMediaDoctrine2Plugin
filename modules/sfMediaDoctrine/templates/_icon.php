<?php use_helper('Text'); ?>

	<a class="btn-warning delete-file" 
	   title="Delete: <?php echo $file->getFilename(); ?>" 
	   href="<?php echo url_for('@sf_media_doctrine_file_delete?file='.$file->getId()) ?>">
		<span class="glyphicon glyphicon-trash"></span>		
	</a>
<div class="icon">
  <?php echo link_to(image_tag($file->getIcon()), $file->getUrl(NULL, NULL, 'original'), array('target' => '_blank')) ?>
</div>

<script>
	$(document).ready(function() {
		$('.delete-file').click(function(e){
			e.stopImmediatePropagation();
			e.preventDefault();
			self = $(this);
			action = $(this).attr('href');
			
			var del = confirm('Desea borrar la imagen?');
			if (del) {
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
				  }
				});
			}
		});
	});
</script>