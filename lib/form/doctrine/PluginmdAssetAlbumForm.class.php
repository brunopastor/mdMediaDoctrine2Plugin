<?php

/**
 * PluginmdAssetAlbum form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginmdAssetAlbumForm extends BasemdAssetAlbumForm
{
  public function setup()
  {
    parent::setup();
    
    unset($this['created_at'], $this['updated_at'], $this['avatar']);
    
    $this->widgetSchema['object_class'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['object_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['relative_path'] = new sfWidgetFormInputHidden();    
  }
}
