<?php
/**
 *
 * 
 */

use vilshub\helpers\Message;
use vilshub\helpers\Style;
use vilshub\validator\Validator;
class Hasher
{
    /**
     * Supported hashing APIs
     * - Password hashing 
     */
    public static function passwordHashing(){
        $body = new class{
            public function hash($data, $algorithm=PASSWORD_BCRYPT, $options=[]){
                $algos = [PASSWORD_DEFAULT, PASSWORD_BCRYPT, PASSWORD_ARGON2I, PASSWORD_ARGON2ID];
                // if(!in_array()) throw new Error("Hasher::passwordHashing");
                return password_hash($data, $algorithm, $options);
            }
            public function verify($data, $hash){
                return password_verify($data, $hash);
            }               
        };
        return $body;
    }
}