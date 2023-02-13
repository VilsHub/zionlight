<?php
/**
 *
 * 
 */

use vilshub\helpers\Message;
use vilshub\helpers\Style;
use vilshub\validator\Validator;
class Process
{
    /**
     * For processing data structures
     * 
     */
    public static function string($string){
        $body = new class ($string) {
            private $string = null;
            public function __construct($string) {
                $this->string = $string;
            }
            public function charAt($position){
                $stringLength = strlen($this->string);
                if($position <= $stringLength && $stringLength > 0){
                    return substr($this->string, ($position-1), 1);
                }else{
                    return null;
                }
            }
            
        };
        return $body;
    }

    public static function array($array){

        $body = new class ($array){
            private $array = null;

            public function __construct($array) {
                $this->array = $array;
            }

            public function next($current, $next){
                /**
                 *  @param integer $current the current ireation index, which started from 0
                 */
                if(($current+$next) <= count($this->array)-1){
                    return $this->array[($current+$next)];
                }else{
                    return null;
                }
            }
            
        };
        return $body;
    }
}