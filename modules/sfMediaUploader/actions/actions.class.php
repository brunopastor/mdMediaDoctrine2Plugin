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

    if($request->hasParameter('md_asset_album_id')){
      $this->album_id = $request->getParameter('md_asset_album_id');
    }else{
      $this->album_id = $this->object->getAlbumes()->getFirst()->getId();    
    }
    
    $this->max_size = ini_get('upload_max_filesize');

    $options['widget'] = array(
        'upload_url' => url_for('sfMediaUploader/uploadAsset?' . ini_get('session.name') . '=' . session_id() . '&upload=mastodonte'), //ruta al action que procesa la imagen y la sube
        'file_types' => sfConfig::get('app_sf_media_browser_upload_content_type', '*.jpg;*.jpeg;*.gif;*.png;*.JPG;*.JPEG;*.GIF'), //formatos soportados
        'max_filesize' => $this->max_size, //peso en bites máximo para cada imagen
        'file_upload_limit' => 0, //cantidad de archivos máximo que podemos subir
        'file_queue_limit' => 0, //
        'progress_style' => sfConfig::get('sf_plugins_upload_javascript_type_', 'swfupload-progressFile'), //     //javascript que dibuja el contenedor de imagenes subidas y thumbnails
        'post_params' => '"object_id":' . $this->object_id . ',"object_class":"' . $this->object_class . '", "md_asset_album_id":"' . $this->album_id . '"', //
        'upload_browse' => '<div id="image-browse" class="btn btn-success">' . 'Buscar' /*__('mdMediaDoctrine_text_uploadFile')*/ . '</div>', //diseño que mostramos el boton de subir, debe mantenerse el id default
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

    $destination_dir = sfConfig::get('sf_web_dir') . '/' . sfConfig::get('app_sf_media_browser_root_dir') . '/' . $base_dir . '/' . $object_dir;

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
  
  public function executeUploaderEmbebidos(sfWebRequest $request)
  {      
      $this->object_class = $request->getParameter('object_class');
      $this->object_id = $request->getParameter('object_id');
      $this->object = Doctrine::getTable($this->object_class)->find($this->object_id);

      $this->type = $request->getParameter('t', 'youtube');
      
      if (!$this->object) {
        $this->getUser()->setFlash('error', 'object error');
        $this->redirect($request->getReferer());
      }

      if($request->hasParameter('md_asset_album_id')){
        $this->album_id = $request->getParameter('md_asset_album_id');
      }else{
        $this->album_id = $this->object->getAlbumes()->getFirst()->getId();    
      }
      $this->class = 'mdAsset' . ucfirst($this->type);
      $classForm = $this->class . 'Form';
      $this->form = new $classForm();

      $template = $this->getContext()->getConfiguration()->getTemplateDir('sfMediaUploader', 'layout.php');
      $this->setLayout($template . '/layout');
  }
  
  public function executeProcessEmbededVideo(sfWebRequest $request)
  {
    // Obtenemos los parametros enviados desde el formulario
    $postParameters = $request->getPostParameters();
    $albumId = $postParameters["albumId"];
    $objectId = $postParameters["objectId"];
    $objectClass = $postParameters["objectClass"];
    $type = $postParameters['mdVideoType'];

    try{
      
      if(!$albumId)
      {

        $object = Doctrine::getTable($objectClass)->find($objectId);
        $instance = mdMediaManager::getInstance(mdMediaManager::MIXED, $object)->load();
        if(!$instance->hasAlbum("default"))
        {
          $options = array('title' => "default", 'description' => 'Este album tendra las imagenes para mostrar.', 'type' => mdMediaManager::MIXED);
          $mdMediaAlbum = $instance->createAlbum($options);
          $albumId = $mdMediaAlbum->getId();

        }
        else
        {
          throw new Exception("Error de javascript", 192);
        }
      }
      switch ($type)
      {
        case mdVideosTypes::YOUTUBE:
            $response = $this->saveYoutube($postParameters, $albumId);
          break;
        case mdVideosTypes::VIMEO:
            $response = $this->saveVimeo($postParameters, $albumId);
          break;
        case mdVideosTypes::ISSUU:
            $response = $this->saveIssuu($postParameters, $albumId);
          break;
        default: 
            $response = array('response' => false);
          break;
      }

      if($response['response'])
      {
        $body = $this->getPartial($type . "Upload", array('form'=> $response['form'], 'albumId' => $albumId, 'objectId' => $objectId, 'objectClass' => $objectClass));
        return $this->renderText(mdBasicFunction::basic_json_response(true, array('body' => $body, 'object_id' => $objectId, 'object_class_name' => $objectClass)));
      }
      else
      {
        $body = $this->getPartial($type . "Upload", array('form'=> $response['form'], 'albumId' => $albumId, 'objectId' => $objectId, 'objectClass' => $objectClass));
        return $this->renderText(mdBasicFunction::basic_json_response(false, array('body' => $body)));
      }
      
    }catch(Exception $e){

        return $this->renderText(mdBasicFunction::basic_json_response(false, array('message' => $e->getMessage())));        
      
    }
  }
  
  private function saveYoutube($postParameters, $album_id)
  {
    $form = new mdMediaYoutubeVideoForm();
    $params = $postParameters[$form->getName()];
    $form->bind($params);
    $response = array();
    
    if($form->isValid())
    {
      $mdYoutubeVideo = new mdMediaYoutubeVideo();
      $mdYoutubeVideo->setMdUserIdTmp ( $this->getUser()->getMdUserId() );
      $mdYoutubeVideo->setSrc($params["src"]);
      $mdYoutubeVideo->setDescription($params["description"]);
      $mdYoutubeVideo->save();
      $album = Doctrine::getTable("mdMediaAlbum")->find($album_id);
      $album->addContent($mdYoutubeVideo);
      $response['response'] = true;
    }
    else
    {
      $response['response'] = false;
    }
    
    $response['form'] = $form;
    
    return $response;
  }
  
  private function saveVimeo($postParameters, $album_id)
  {
    $form = new mdMediaVimeoVideoForm();
    $params = $postParameters[$form->getName()];
    $form->bind($params);
    
    if($form->isValid())
    {
      $mdVimeoVideo = new mdMediaVimeoVideo();
      $mdVimeoVideo->setMdUserIdTmp ( $this->getUser()->getMdUserId() );
      $mdVimeoVideo->setVimeoUrl($params["vimeo_url"]);
      $mdVimeoVideo->save();
      $album = Doctrine::getTable("mdMediaAlbum")->find($album_id);
      $album->addContent($mdVimeoVideo);
      $response['response'] = true;
    }
    else
    {
      $response['response'] = false;
    }
    
    $response['form'] = $form;
    
    return $response;   
  }
  
  private function saveIssuu($postParameters, $album_id)
  {
    $form = new mdMediaIssuuVideoForm();
    $params = $postParameters[$form->getName()];
    $form->bind($params);
    $response = array();
    
    if($form->isValid())
    {
      $mdIssuuVideo = new mdMediaIssuuVideo();
      $mdIssuuVideo->setMdUserIdTmp ( $this->getUser()->getMdUserId() );
      $mdIssuuVideo->setEmbedCode($params["embed_code"]);
      $mdIssuuVideo->save();
      $album = Doctrine::getTable("mdMediaAlbum")->find($album_id);
      $album->addContent($mdIssuuVideo);
      $response['response'] = true;
    }
    else
    {
      $response['response'] = false;
    }
    
    $response['form'] = $form;
    
    return $response;
  }
}
