<div class="container">
	<?php use_stylesheets_for_form($form) ?>
	<?php use_javascripts_for_form($form) ?>
	<div id="preview">
	<?php if($yt = $album->getYoutube()): ?>
		<iframe width="560" height="315"
		src="<?php echo '//www.youtube.com/embed/'.$yt->getPath() ?>">
		</iframe>
	<?php endif; ?>
	</div>
	<?php echo $form['path']->render(array('id' => 'url', 'class' => 'form-control youtube', 'placeholder' => 'Ingrese el codigo del video de youtube..')) ?>
	<button id="preview-btn" class="btn btn-warning">Previsualizar</button>
	<input id="submit" type="submit" class="btn btn-success" data-target="<?php echo url_for('sf_media_doctrine_save_yt') ?>" data-album = "<?php echo $album_id ?>">
</div>

<script type="text/javascript">
	$('#preview-btn').click(function(){
		var url = $('#url').val();
		$('#preview').html('<iframe width="560" height="315" src="//www.youtube.com/embed/'+url+'"></iframe>')
	})

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
			complete: function(json){
				parent.$.fancybox.close();
				parent.window.location.reload()
			}
		})
	})
</script>