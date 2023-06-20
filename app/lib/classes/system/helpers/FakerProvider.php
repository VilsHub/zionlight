<?php
/**
 * Class for extending PHP Faker fake data categories
 */
use Faker\Provider\Base;

class FakerProvider extends Base
{
    // Define your custom methods here

    public function age($min, $max=null){
        $min = $min < 0? 0 : $min;

        if($max == null || $max < 0){
            $max = $min;
        }

        $rand = rand($min, $max);
        
        return $rand;
    }
}

?>