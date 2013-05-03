<?php

/**
 *
 *
 * @package     mdMediaDoctrine2
 * @author      Gaston Caldeiro <chugas488@gmail.com>
 */
class BasesfMediaDoctrineComponents extends sfComponents
{
  public function executeIcon(sfWebRequest $request)
  {
    $class = sfMediaBrowserUtils::getTypeFromExtension(sfMediaBrowserUtils::getExtensionFromFile($this->file_url)) == 'image'
           ? 'sfMediaBrowserImageObject'
           : 'sfMediaBrowserFileObject'
           ;
    $this->file = new $class($this->file_url);
  }
  
  public function executeGallery(sfWebRequest $request) {
    // list of albumes of object $object
    $object = $this->object;
    
    $this->dirs = $object->getAlbumes();
    $this->files = array();
    
    if(!isset($this->mdAssetAlbum)){
      $this->mdAssetAlbum = false;
      
      if($this->dirs->count() > 0)
      {
        // list of files in default album
        $this->files = $object->getFiles($this->dirs->getFirst()->getId());
        $this->mdAssetAlbum = $this->dirs->getFirst();
      }else{
        $this->mdAssetAlbum = mdAssetAlbum::create($object);
      }
    }else{
      $this->files = $object->getFiles($this->mdAssetAlbum->getId());
    }
    // forms
    $this->upload_form = new mdAssetFileForm();
    $this->album_form = new mdAssetAlbumForm();
    $this->album_form->setDefault('object_class', $object->getObjectClass());
    $this->album_form->setDefault('object_id', $object->getObjectId());
  }
  
}
