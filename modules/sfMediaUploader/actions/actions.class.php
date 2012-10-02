<?php

/**
 * sfMediaUploader actions.
 *
 * @package    Plugins
 * @subpackage mdMediaDoctrine2Plugin
 * @author     Gaston Caldeiro
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfMediaUploaderActions extends sfActions {

  public function executeUploader(sfWebRequest $request) {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url', 'I18N'));

    $this->object_class = $request->getParameter('object_class');
    $this->object_id = $request->getParameter('object_id');
    $this->object = Doctrine::getTable($this->object_class)->find($this->object_id);
    if (!$this->object) {
      $this->getUser()->setFlash('error', 'object error');
      $this->redirect($request->getReferer());
    }

    $this->album_id = $this->object->getAlbumes()->getFirst()->getId();    
    
    $this->max_size = ini_get('upload_max_filesize');

    $options['widget'] = array(
        'upload_url' => url_for('sfMediaUploader/uploadAsset?' . ini_get('session.name') . '=' . session_id() . '&upload=mastodonte'), //ruta al action que procesa la imagen y la sube
        'file_types' => sfConfig::get('sf_plugins_upload_content_type_', '*.jpg;*.jpeg;*.gif;*.png;*.JPG;*.JPEG;*.GIF;*.PNG'), //formatos soportados
        'max_filesize' => $this->max_size, //peso en bites máximo para cada imagen
        'file_upload_limit' => 0, //cantidad de archivos máximo que podemos subir
        'file_queue_limit' => 0, //
        'progress_style' => sfConfig::get('sf_plugins_upload_javascript_type_', 'swfupload-progressFile'), //     //javascript que dibuja el contenedor de imagenes subidas y thumbnails
        'post_params' => '"object_id":' . $this->object_id . ',"object_class":"' . $this->object_class . '"', //
        'upload_browse' => '<div id="image-browse" class="addbutton">' . __('mdMediaDoctrine_text_uploadFile') . '</div>', //diseño que mostramos el boton de subir, debe mantenerse el id default
        'debug' => false
    );

    $this->form = new SWFUploadForm(array(), array(), $options);

    $template = $this->getContext()->getConfiguration()->getTemplateDir('sfMediaUploader', 'layout.php');
    $this->setLayout($template . '/layout');
  }

  public function executeUploadAsset(sfWebRequest $request) {
    if (!$this->getUser()->isAuthenticated()) {
      return $this->renderText("NOK - No esta autentificado");
    }

    sfConfig::set('sf_web_debug', false);
    $this->setLayout(false);

    return $this->createFile($request);
  }

  public function createFile(sfWebRequest $request) {
    // Obtenemos parametros: object_class, object_id
    $object_class = $request->getParameter('object_class');
    $object_id = $request->getParameter('object_id');
    $object = Doctrine::getTable($object_class)->find($object_id);
    if (!$object) {
      $this->getUser()->setFlash('error', 'object error');
      return false;
    }
    $base_dir = $object->getBaseDir();
    $object_dir = $object->getObjectDir();

    $destination_dir = sfConfig::get('sf_web_dir') . sfConfig::get('app_sf_media_browser_root_dir') . '/' . $base_dir . '/' . $object_dir;

    if (sfConfig::get('app_sf_media_browser_albums_enabled')) {
      
      $md_asset_album_id = $request->getParameter('md_asset_album_id');
      
    } else {
      
      $mdAssetAlbumes = $object->getAlbumes();
      if($mdAssetAlbumes->count() == 0){
        $mdAssetAlbum = mdAssetAlbum::create($object);
      }else{
        $mdAssetAlbum = $mdAssetAlbumes->getFirst();
      }
      $md_asset_album_id = $mdAssetAlbum->getId();
      
    }

    $form = new mdAssetFileForm(null, array('path' => $destination_dir));

    $parameters = array(
        "md_album_id" => $md_asset_album_id,
        "description" => "",
        "id" => "",
        "extension" => "",
        "type" => "",
        "filesize" => "",
        "_csrf_token" => $form->getCSRFToken()
    );

    $form->bind($parameters, $_FILES);

    if ($form->isValid()) {
      try {

        $md_asset = $form->save();
      } catch (Exception $e) {

        $this->getUser()->setFlash('error', $e->getMessage());
        return $this->renderText("NOK " . $e->getMessage());
      }

      $this->getUser()->setFlash('notice', 'file.create');

      return $this->renderText("OK");
    } else {

      $this->getUser()->setFlash('error', 'file.create');
      return $this->renderText("NOK");
    }
  }

}
