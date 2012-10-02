<?php

class mdCustomValidatedFile extends sfValidatedFile {

  public function generateFilename() {
    return self::doFilename($this->getOriginalName());
  }
  
  public static function doFilename($original_name){
    $name = sfMediaBrowserStringUtils::slugify(pathinfo($original_name, PATHINFO_FILENAME));

    $ext = pathinfo($original_name, PATHINFO_EXTENSION);

    return ($ext ? $name . '.' . $ext : $name);  
  }
  
  public function toArray(){
    return array(
        "filename" =>
        array(
            "error" => 0,
            "name" => $this->getOriginalName(),
            "type" => $this->getType(),
            "tmp_name" => $this->getTempName(),
            "size" => $this->getSize()
        )
    );
  }

}