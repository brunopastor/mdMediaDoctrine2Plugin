<?php
/**
 * Parametros configurables:
 * all:
 *  sf_media_browser:
 *    root_dir: uploads
 *    cache_dir: cache
 * 
 * MODO DE USO:
 * use_helper('mdMedia')
 * 
 * get_url('productos/archivo.jpg', 200, 100, 'resize');
 */

function get_url($filename, $width, $height, $code = 'original', $proportional = false, $left = 0, $top = 0) {
  if($code == 'original') return '/' . sfConfig::get('app_sf_media_browser_root_dir') . '/' . $filename;
  
  $root_path = realpath(sfConfig::get('sf_web_dir') . '/' . sfConfig::get('app_sf_media_browser_root_dir'));
  $cache_path = realpath(sfConfig::get('sf_web_dir') . '/' . sfConfig::get('app_sf_media_browser_cache_dir'));

  $dirs = explode('/', $filename);
  $_filename = array_pop($dirs);
  $_dirs = implode('/', $dirs);

  $cacheRootDir = $cache_path . '/' . $_dirs . '/'  . $width . 'x' . $height;

  $cacheFile = $cacheRootDir . '/' . $_filename;

  if (!file_exists($cacheFile)) {
    try {
      $img = new sfImage($root_path . '/' . $filename);

      switch ($code) {
        case 'resize': $img->resize($width, $height, true, $proportional);
          break;
        case 'crop': $img->crop($left, $top, $width, $height);
          break;
        case 'resizecrop': $img->resizecrop($width, $height);
          break;
        default: throw new Exception('invalid code');
          break;
      }

      if (!sfMediaBrowserUtils::checkDirectory($cacheRootDir)) {
        throw new Exception('Filesystem fail');
      }

      $img->saveAs($cacheFile);
    } catch (Exception $e) {

      return '/' . sfConfig::get('app_sf_media_browser_root_dir') . '/' . $filename;

    }
  }

  return '/' . sfConfig::get('app_sf_media_browser_cache_dir') . '/' . $_dirs. '/' . $width . 'x' . $height . '/' . $_filename;
}
