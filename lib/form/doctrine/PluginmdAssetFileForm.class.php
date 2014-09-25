<?php

/**
 * PluginmdAssetFile form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginmdAssetFileForm extends BasemdAssetFileForm {

  public function setup() {

    parent::setup();

    unset($this['created_at'], $this['updated_at'], $this['original_extension']);

    $this->widgetSchema['filesize'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['type'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['extension'] = new sfWidgetFormInputHidden();

    $this->widgetSchema['filename'] = new sfWidgetFormInputFile();

    $this->validatorSchema['filename'] = new sfValidatorFile(
                    array(
                        'path' => (is_null($this->getOption('path')) ? sfConfig::get('sf_upload_dir') : $this->getOption('path')),
                        'validated_file_class' => 'mdCustomValidatedFile'
            ));
  }

  public function save($con = null) {
    
    $post_file = $this->getValue('filename');
    $array_name = explode('.',$post_file->getOriginalName());
    $extension = array_pop($array_name);
    $params = array(
        'type' => $post_file->getType(),
        'filesize' => $post_file->getSize(),
        'extension' => '.'.$extension
    );

    $tainted = $this->getTaintedValues();

    $data = array_merge($tainted, $params);

    $ext = pathinfo($post_file->getOriginalName(), PATHINFO_EXTENSION);

    if (sfMediaBrowserUtils::getTypeFromExtension($ext) == 'file') {
      throw new Exception('Invalid type');
    }

    if (!sfMediaBrowserUtils::checkDirectory($this->getOption('path'))) {
      throw new Exception('Filesystem fail');
    }

    // thumbnail
    if (sfConfig::get('app_sf_media_browser_thumbnails_enabled', false) && sfMediaBrowserUtils::getTypeFromExtension($ext) == 'image') {
      $this->generateThumbnail($post_file->getTempName(), mdCustomValidatedFile::doFilename($post_file->getOriginalName()));
    }

    $this->bind($data, $post_file->toArray());

    return parent::save($con);
  }

  /**
   * @TODO
   * @param $file
   */
  protected function generateThumbnail($source_file, $destination_name) {
    $destination_dir = (is_null($this->getOption('path')) ? sfConfig::get('sf_upload_dir') : $this->getOption('path'));

    if (!class_exists('sfImage')) {
      throw new sfException('sfImageTransformPlugin must be installed in order to generate thumbnails.');
    }
    $thumb = new sfImage($source_file);

    $thumb->thumbnail(sfConfig::get('app_sf_media_browser_thumbnails_max_width', 64), sfConfig::get('app_sf_media_browser_thumbnails_max_height', 64));

    $destination_dir = $destination_dir . '/' . sfConfig::get('app_sf_media_browser_thumbnails_dir');

    if (!sfMediaBrowserUtils::checkDirectory($destination_dir)) {
      throw new Exception('Filesystem fail');
    }

    return $thumb->saveAs($destination_dir . '/' . $destination_name);
  }

}
