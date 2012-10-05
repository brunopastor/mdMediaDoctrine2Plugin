<?php

/**
 * PluginmdAssetAlbum
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginmdAssetAlbum extends BasemdAssetAlbum
{
  public function hasAvatar()
  {
    return !is_null($this->getMdAssetFileId()) && $this->getMdAssetFileId() != '';
  }
  
  public function getUrl($width, $height, $original = false){
    if($this->hasAvatar()){
      return $this->getMdAsset()->getUrl($width, $height, $original);      
    }else{
      return false;
    }
  }
}