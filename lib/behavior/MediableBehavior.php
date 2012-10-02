<?php

class Doctrine_Template_Mediable extends Doctrine_Template {

  public function getObjectClass(){
    return $this->getInvoker()->getObjectClass();
  }
  
  public function getObjectId(){
    return $this->getInvoker()->getId();    
  }

  public function getObjectDir(){
    return $this->getBaseDir() . '_' . $this->getObjectId();
  }

  public function getBaseDir(){
    if(method_exists($this->getInvoker(), 'getBaseDir')){
      return $this->getInvoker()->getObjectClass();
    }
    return $this->getObjectClass();
  }

  public function getAlbumes(){
    return Doctrine::getTable('mdAssetAlbum')->retrieveByObject($this->getObjectClass(), $this->getObjectId());
  }

  public function getFiles($md_album_id){
    return Doctrine::getTable('mdAssetFile')->retrieveFiles($md_album_id);
  }

}
