<?php
class ViewHelper {
 private $block = [];
 
 function get_block($index=0){
  if($index===0){
   return implode('\n', $this->block);
  } else {
   if(is_array($index)){
    $return = array();
    foreach($index as $idx){
     $return = array_merge($return, $this->block[$idx]);
    }
   } else {
    $return = isset($this->block[$index]) ? $this->block[$index] : '';
   }
   return $return;
  }
 }
 
 function start_block($index){
  $this->block[$index] = "";
  ob_start();
 }
 
 function end_block($index){
  $this->block[$index] = ob_get_clean();
 }

 function reset_block(){
  $this->block = [];
 }

 function set_flash_message($message, $additional_param=null, $key='flashmsg'){
    $to_session = array('message'=> $message);
    if($additional_param !== null)
        $to_session = $to_session + $additional_param;
    $_SESSION[$key] = $to_session;
 }

 function get_flash_message($key='flashmsg', $cleanup=true){
    $flash_session = $_SESSION[$key];
    if($cleanup)
        unset($_SESSION[$key]);
    return $flash_session;
 }

 function check_flash($key='flashmsg'){
    return isset($_SESSION[$key]);
 }
}

$template = new ViewHelper();