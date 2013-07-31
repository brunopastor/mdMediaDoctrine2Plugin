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

  public function getFiles($md_album_id = null){
    if(is_null($md_album_id)){
      $md_album = $this->getAlbumes()->getFirst()->getId();
    }else{
      $md_album = Doctrine::getTable('mdAssetAlbum')->find($md_album_id);
    }
    return Doctrine::getTable('mdAssetFile')->getFilesByPosition($md_album);
  }


  /**
   * return boolean if has avatar from given album (or default if its not enable album usage)
   *
   * @return void
   * @author 
   **/
  public function hasAvatar($mdAssetAlbum = null){
    if(sfConfig::get('app_sf_media_browser_albums_enabled', false)){
      if($mdAssetAlbum === null){
        throw new Exception("Album must be implicit", 1);
      }
      //TODO manage multiple albums logic
    }else{
      $mdAssetAlbum = $this->getAlbumes()->getFirst();
    }
    return (!$mdAssetAlbum ? false : $mdAssetAlbum->hasAvatar());
  }

  
/**
   * return avatar file object from given album (or default if its not enable album usage)
   *
   * @return void
   * @author 
   **/
  public function getAvatar($mdAssetAlbum = null){
    if(sfConfig::get('app_sf_media_browser_albums_enabled', false)){
      if($mdAssetAlbum === null){
        throw new Exception("Album must be implicit", 1);
      }
      //TODO manage multiple albums logic
    }else{
      $mdAssetAlbum = $this->getAlbumes()->getFirst();
    }
    return $mdAssetAlbum->getMdAsset();
  }


  /**
   * return avatar url from given album (or default if its not enable album usage)
   *
   * @return void
   * @author 
   **/
  public function getAvatarSrc($mdAssetAlbum = null){
    if(sfConfig::get('app_sf_media_browser_albums_enabled', false)){
      if($mdAssetAlbum === null){
        throw new Exception("Album must be implicit", 1);
      }
      //TODO manage multiple albums logic
    }else{
      $mdAssetAlbum = $this->getAlbumes()->getFirst();
    }
    return $mdAssetAlbum->getAvatarFilename();
  }


}
