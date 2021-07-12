<?php
/**
 *
 */
  /**
  *
  */
  class DataParser
  {
    public static function inText($value){
      if(is_string($value)){
        return strip_tags($value);
      }
    }
  }
?>