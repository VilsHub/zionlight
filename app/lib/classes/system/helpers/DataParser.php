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
        $value = htmlentities( $value, ENT_QUOTES, 'utf-8' );
        $value = htmlspecialchars($value);
        $value = strip_tags($value);
        return $value;
      }
    }
  }
?>