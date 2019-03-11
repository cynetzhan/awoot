<?php
function module_url($module_name, $page_name="index", $param="", $apps_name=""){
global $config;
 $url = "index.php?module=$module_name&page=$page_name";
 if($apps_name!==""){
  $url .= "&apps=$apps_name";
 }
 if($param !== ""){
     $url .= "&".$param;
 }
 return $url;
}

function module_label($module_name, $page_name){
 $query = fetchQuery(getData("access_name", "access", "where access_controller='$module_name' and access_action='$page_name'"));
 return isset($query[0]['access_name'])?$query[0]['access_name']:"Halaman Tanpa Nama";
}

function redirect($url){
 header('Location: '.$url);
}