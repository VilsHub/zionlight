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
     * - Password_hashing 
     */
    public static function passwordHashing(){
        $body = new class{
            public function hash($data, $algorithm=PASSWORD_BCRYPT, $options=[]){
                $algos  = [PASSWORD_DEFAULT, PASSWORD_BCRYPT, PASSWORD_ARGON2I, PASSWORD_ARGON2ID];
                return password_hash($data, $algorithm, $options);
            }
            public function verify($data, $hash){
                // @hash : The hash data to be verified with the raw data ($data)
                return password_verify($data, $hash);
            }               
        };
        return $body;
    }
}