<?php
function is_empty($data){
    if(is_Object($data)){
        $arr = (array) $data;
        return count($arr) > 0?false:true;
    }
}

?>