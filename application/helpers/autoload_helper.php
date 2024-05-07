<?php
function _post(){
    $json = file_get_contents('php://input');
    $array = json_decode($json, true);
    return $array;
}
function pre($array){
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}