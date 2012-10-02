  <?php
  class chForm extends sfFormSymfony
  {
    public function setup()
    {
      $this->setWidget('image', new sfWidgetFormInputMediaBrowser());
      $this->setValidator('image', new sfValidatorMediaBrowserFile());
    }
  }