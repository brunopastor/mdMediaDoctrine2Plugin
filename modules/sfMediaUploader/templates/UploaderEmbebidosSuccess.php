<div class="container">
	<?php use_stylesheets_for_form($form) ?>
	<?php use_javascripts_for_form($form) ?>

	<?php echo $form['path']->render(array('id' => 'url', 'class' => 'form-control youtube', 'placeholder' => 'Url al video..')) ?>
	<input id="submit" type="submit" class="btn btn-success" data-target="<?php echo url_for('sf_media_doctrine_save_yt') ?>" data-album = "<?php echo $album_id ?>">
</div>

<script type="text/javascript">
	$('#submit').click(function(event){
		event.preventDefault();
		var send = $(this).attr('data-target');
		var url = $('#url').val();
		var album_id = $(this).attr('data-album');

		var data = {
			url : url,
			album_id : album_id,
		}

		$.ajax ({
			url : send,
			data: data,
			dataType: 'json',
			success: function(json){
				if (json.response == 'OK'){
					$fancybox.close();
				}
			}
		})
	})
</script>