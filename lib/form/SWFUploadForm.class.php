<?php

class SWFUploadForm extends sfForm {

  public $options_data = array();

  public function configure() {
    $this->setWidgets(array(
        'filename' => new sfWidgetFormInputSWFUpload(array(), array(), $this->options_data)
    ));

    $this->setValidators(array(
        'filename' => new sfValidatorFile()
    ));
  }

  public function __construct($defaults, $options, $options_data) {
    $this->options_data = $options_data;

    parent::__construct($defaults, $options);
  }

}
