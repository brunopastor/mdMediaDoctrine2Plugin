mdMediaDoctrine2Plugin
======================

USO BASICO:

Para renderizar imagenes sin tener el behavior Mediable se debe:

1- Incluir helper mdMedia: <?php use_helper('mdMedia'); ?>

2- Invocar la funcion get_url cuya firma es: get_url($filename, $width, $height, $code = 'original', $proportional = false, $left = 0, $top = 0)

Los parametros minimos que recibe son:
$filename: nombre del archivo
$width: Ancho pedido
$height: Alto pedido
$code: (resize, resizecrop, original, crop) 
Si $code es 'original' los parametros width y height no se usaran.
Si $code es 'resize' se podra pasar un parametro adicional $proportional de tipo boolean
Si $proportional es true se mantendran proporciones de la imagen
Si $proportional es false no.
Si $code es 'crop' se podran especificar otros parametros adicionales: $left, $top necesarias para determinar el corte de la imagen.

Ejemplo:
<?php echo get_url('productos/archivo.jpg', 200, 100, 'resize'); ?>

USO AVANZADO:

Para hacer un uso avanzado de la media se debe tener un objeto en el schema con el behavior Mediable, ej:

mdProduct:
actAs:
Timestampable: ~
Mediable: ~    
columns:
id:
type: integer(4)
primary: true
autoincrement: true
name:
type: string(32)
notnull: true

El objeto mdProduct tiene los siguientes metodos heredados de la interfaze Mediable:
getObjectClass();
getObjectId();
getObjectDir();
getBaseDir();
getAlbumes();
getFiles($md_album_id);

(PARA IMAGENES) Para listar todos los archivos de un objeto $object de tipo mdProduct se debera:

1- Obtener el album del mdProduct: $md_asset_album = $object->getAlbumes()->getFirst();

2- Obtener los archivos: $this->files = $object->getFiles($md_asset_album->getId());

3- Mostrar avatar
<?php if ($md_asset_album->hasAvatar()): ?>
  <img src="<?php echo $md_asset_album->getUrl(220, 272, 'resize'); ?>" />
<?php endif; ?>

4- Mostrar las imagenes en un bucle ej:
<?php foreach ($files as $file): ?>

  <img src="<?php echo $file->getUrl(200, 100, 'resize', true); ?>" />

<?php endforeach ?>

TODO--------------------------------------------------------------------------
Se tendria que poder hacer asi:
$files = $object->getFiles(); // puede recibir el id del album o nada 
$has   = $object->hasAvatar() // puede recibir el objeto mdAssetAlbum o nada
$file  = $object->getAvatar() // puede recibir el objeto mdAssetAlbum o nada
------------------------------------------------------------------------------
