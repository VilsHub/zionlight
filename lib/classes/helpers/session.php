<?php
/**
 *
 */
  /**
  *
  */
  class session
  {
    public static function start($prependName = null){
        $sessionID = $prependName == null?"ZLight":"ZLight - ".$prependName;
        session_name($sessionID);
        session_start();
    }
}