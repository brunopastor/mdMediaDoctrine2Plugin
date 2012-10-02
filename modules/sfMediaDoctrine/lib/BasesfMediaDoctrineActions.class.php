<?php

/**
 *
 * @package     sfMediaDoctrine
 * @author      Gaston Caldeiro <chugas488@gmail.com>
 *
 */
class BasesfMediaDoctrineActions extends sfActions {

  public function preExecute() {}

  public function executeCreateDirectory(sfWebRequest $request) {
    $form = new mdAssetAlbumForm();
    
    $postParameters = $request->getParameter($form->getName());
    
    $object_class = $postParameters['object_class'];
    $object_id = $postParameters['object_id'];
    $object = Doctrine::getTable($object_class)->find($object_id);
    
    if(!$object){
      $this->getUser()->setFlash('error', 'object error');
      $this->redirect($request->getReferer());
    }
    
    $base_dir = $object->getBaseDir();
    $object_dir = $object->getObjectDir();

    $relative_path = sfConfig::get('app_sf_media_browser_root_dir') . '/' . $base_dir . '/' . $object_dir;

    $params = array(
      'relative_path' => $relative_path
    );

    $form->bind(array_merge($postParameters, $params));

    if ($form->isValid()) {

      $md_asset_album = $form->save();
      
      $path = sfConfig::get('sf_web_dir') . $relative_path;
      
      sfMediaBrowserUtils::checkDirectory($path);

      $this->getUser()->setFlash('notice', 'directory.create');
      
    } else {

      $this->getUser()->setFlash('error', 'directory.create');

    }

    $this->redirect($request->getReferer());
  }

  public function executeCreateFile(sfWebRequest $request) {
    // Obtenemos parametros: object_class, object_id
    $object_class = $request->getParameter('object_class');
    $object_id = $request->getParameter('object_id');
    $object = Doctrine::getTable($object_class)->find($object_id);
    if(!$object){
      $this->getUser()->setFlash('error', 'object error');
      $this->redirect($request->getReferer());
    }
    $base_dir = $object->getBaseDir();
    $object_dir = $object->getObjectDir();

    $destination_dir = sfConfig::get('sf_web_dir') . sfConfig::get('app_sf_media_browser_root_dir') . '/' . $base_dir . '/' . $object_dir;

    $form = new mdAssetFileForm(null, array('path' => $destination_dir));

    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

    if ($form->isValid()) {
      try {

        $form->save();
        
      } catch (Exception $e) {

        $this->getUser()->setFlash('error', $e->getMessage());
      }

      $this->getUser()->setFlash('notice', 'file.create');
    } else {

      $this->getUser()->setFlash('error', 'file.create');

    }

    $this->redirect($request->getReferer());
  }  
  
  public function executeDeleteAlbum(sfWebRequest $request) {

  }

  public function executeDeleteFile(sfWebRequest $request) {
    $md_asset_file = Doctrine::getTable('mdAssetFile')->find($request->getParameter('file'));

    $is_avatar = ($md_asset_file->getMdAssetAlbum()->getMdAssetFileId() == $md_asset_file->getId());    
    
    if($is_avatar){
      $md_asset_file->getMdAssetAlbum()->setMdAssetFileId(NULL);
      $md_asset_file->getMdAssetAlbum()->save();
    }    
    
    if($md_asset_file){
      $md_asset_file->delete();      
    }
    
    return $this->renderText(mdBasicFunction::basic_json_response(true, array('is_avatar' => $is_avatar)));
  }
  
  public function executeSortFile(sfWebRequest $request) {
    $priorities = explode(',', $request->getParameter('priorities'));
    $i = 1;
    foreach ($priorities as $data){
      $info = explode('_', $data);
      $md_asset_file = Doctrine::getTable('mdAssetFile')->find($info[1]);
      $md_asset_file->setPosition($i);
      $md_asset_file->save();
      $i++;
    }
    
    return $this->renderText(mdBasicFunction::basic_json_response(true, array()));
  }  
  
  public function executeAssetUpdate(sfWebRequest $request){
    $object_class = $request->getParameter('object_class');
    $object_id = $request->getParameter('object_id');
    $object = Doctrine::getTable($object_class)->find($object_id);
    
    if(!$object){
      $this->getUser()->setFlash('error', 'object error');
      return $this->renderText(mdBasicFunction::basic_json_response(false, array()));
    }
    
    $albumes = $object->getAlbumes();
    $mdAssetAlbum = false;

    if($albumes->count() > 0)
    {
      $mdAssetAlbum = $albumes->getFirst();
    }

    $files = $object->getFiles($mdAssetAlbum->getId());
    
    $partial = $this->getPartial('sfMediaDoctrine/list_files', array('files' => $files, 'mdAssetAlbum' => $mdAssetAlbum));
    
    return $this->renderText(mdBasicFunction::basic_json_response(true, array('response' => $partial)));
  }
  
  public function executeChangeAvatar(sfWebRequest $request){
    // Recibe: md_asset_album_id y md_asset_file_id
    $md_asset_album_id = $request->getParameter('md_asset_album_id');
    $md_asset_file_id = $request->getParameter('md_asset_file_id');
    
    $md_asset_album = Doctrine::getTable('mdAssetAlbum')->find($md_asset_album_id);
    $md_asset_file = Doctrine::getTable('mdAssetFile')->find($md_asset_file_id);
    if($md_asset_file && $md_asset_album){
      if($md_asset_file->getMdAlbumId() == $md_asset_album_id) {
        $md_asset_album->setMdAssetFileId($md_asset_file_id);
        $md_asset_album->save();
      }
    }

    return $this->renderText(mdBasicFunction::basic_json_response(true, array('response' => $md_asset_file->getUrl(220, 272))));
  } 
  
  /**
   * @todo Get rid of the purpose of urldecode for 'dir' parameter
   */
  /*public function executeMove(sfWebRequest $request) {
    $file = new sfMediaBrowserFileObject($request->getParameter('file'));
    $dir = new sfMediaBrowserFileObject(urldecode($request->getParameter('dir')));
    $new_name = $dir->getPath() . '/' . $file->getName(true);

    $error = null;
    try {
      $moved = rename($file->getPath(), $new_name);
    } catch (Exception $e) {
      $error = $e;
    }

    if ($request->isXmlHttpRequest()) {
      sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N'));
      if ($error) {
        $reponse = array('status' => 'error', 'message' => __('Some error occured.'));
      } elseif ($moved) {
        $response = array('status' => 'notice', 'message' => __('The file was successfully moved.'));
      } elseif (file_exists($new_name)) {
        $response = array('status' => 'error', 'message' => __('A file with the same name already exists in this folder.'));
      } else {
        $response = array('status' => 'error', 'message' => __('Some error occured.'));
      }
      return $this->renderText(json_encode($response));
    }
    $this->redirect($request->getReferer());
  }

  public function executeRename(sfWebRequest $request) {
    $file = new sfMediaBrowserFileObject($request->getParameter('file'));
    $name = sfMediaBrowserStringUtils::slugify(pathinfo($request->getParameter('name'), PATHINFO_FILENAME));
    $ext = $file->getExtension();
    $valid_filename = $ext ? $name . '.' . $ext : $name;
    $new_name = dirname($file->getPath()) . '/' . $valid_filename;

    $error = null;
    try {
      $renamed = rename($file->getPath(), $new_name);
    } catch (Exception $e) {
      $error = $e;
    }

    if ($request->isXmlHttpRequest()) {
      sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N'));
      if ($error) {
        $reponse = array('status' => 'error', 'message' => __('Some error occured.'));
      } elseif ($renamed) {
        $response = array('status' => 'notice', 'message' => __('The file was successfully renamed.'), 'name' => $valid_filename, 'url' => dirname($file->getUrl()) . '/' . $valid_filename);
      } elseif (file_exists($new_name)) {
        $response = array('status' => 'error', 'message' => __('A file with the same name already exists in this folder.'));
      } else {
        $response = array('status' => 'error', 'message' => __('Some error occured.'));
      }
      return $this->renderText(json_encode($response));
    }
    $this->redirect($request->getReferer());
  }*/  

  /*protected function createFileObject($file) {
    $class = sfMediaBrowserUtils::getTypeFromExtension(pathinfo($file, PATHINFO_EXTENSION)) == 'image' ? 'sfMediaBrowserImageObject' : 'sfMediaBrowserFileObject';
    return new $class($file);
  }*/

  /**
   *
   * @param string $root_path
   * @param string $dir
   * @return mixed <string, boolean>
   */
  /*protected function isPathSecured($root_path, $requested_path) {
    return preg_match('`^' . realpath($root_path) . '`', realpath($requested_path));
  }*/
  
}
