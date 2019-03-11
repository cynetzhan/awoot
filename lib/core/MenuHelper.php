<?php
function LeftMenu($array, $head="<ul class='nav side-menu'>"){
 $normal = 0;
 if(!empty($array))
 {
		$menu=$head;
	}
	  
 foreach($array as $element)
 {
  $normal++;
  if($element['visible']==1){
   if(LeftMenu($element['items'],'<ul class="treeview-menu">')!='')
   {
    $menu.= '<li class="treeview"><a href="'.$element['url'].'"><i class="'.$element['icon'].'"></i><span class="title">'.$element['label'].'</span>';
   }
  }
 }
}

function navLevel($lv=1){
 switch($lv){
  case 2:
   $class = 'second';
  break;
  
  case 3:
   $class = 'third';
  break;
  
  default:
   $class = '';
  break;
 }
 return $class;
}

function navMenu($items, $head="<ul class='nav' id='side-menu'>"){
 global $config;
 $navHtml = $head;
 static $level = 1;
 foreach($items as $item){
  // trigger_error($item['access_controller']." ".$item['access_action']." is checking for iduser: ".$_SESSION['id_user']);
/*  if(getAuth($item['access_controller'], $item['access_action']))
  { */

   $activeMenu = ($config['module'] == $item['access_controller']) && ($config['page'] == $item['access_action']);
   $menuUrl = ($item['access_controller'] !== null) ? module_url($item['access_controller'], $item['access_action']) : '#';
   $menuLabel = ($item['access_name'] !== null) ? $item['access_name'] : $item['menu_arrange_name'];
   
   $navHtml .= "<li> <a href='$menuUrl'";
   
   if($activeMenu)
    $navHtml .= " class='waves-effect active'>";
   else
    $navHtml .= " class='waves-effect'>";
   
   $navHtml .= "<i class='".$item['menu_icon']." fa-fw'></i>";
   
   if(isset($item['child']))
   {
    $level++;
    $navHtml .= "<span class='hide-menu'> ".$menuLabel."<span class='fa arrow'></span></span></a>";
    $navHtml .= navMenu($item['child'], "<ul class='nav nav-".navLevel($level)."-level'>");
   }
   else
   {
    $navHtml .= "<span class='hide-menu'> ".$menuLabel."</span></a>";
   }
   
   $navHtml .= "</li>";
  //}
 }
 $navHtml .= "</ul>";
 $level = 1;
 return $navHtml;
}

function getMenuAccess($parent_id=0, $visible=1){
 $navElement = array();
 $navQuery = getData("a.*, ma.menu_icon", "access a left join access_menu_arrange ma on a.access_id = ma.access_id", "where a.access_visible=$visible and a.access_parent=".$parent_id);
 while($row=mysqli_fetch_assoc($navQuery))
 {
  if($row['access_status'] === '0') continue; // exclude inactive access (continue iteration to next access)
  $navElement[$row['access_id']] = $row;
  $navChildMenu = getMenuAccess($row['access_id']);
  if(count($navChildMenu) > 0)
   $navElement[$row['access_id']]['child'] = $navChildMenu;
 }
 return $navElement;
}

function sortMenuArrange($sort_key){
 return function($a, $b) use ($sort_key){
  if ($a[$sort_key] == $b[$sort_key]) {
   return 0;
  }
   return ($a[$sort_key] < $b[$sort_key]) ? -1 : 1;
 };
}

function getMenuArrange($parent_id = 0, $sort_menu_by='menu_arrange_order', $group_id=false){
 $navMenu = array();
 $group_id = ($group_id !== false) ? "and menu_group_id = '$group_id'" : "";
 $navQuery = getData("a.*, ma.*","access a right join access_menu_arrange ma on a.access_id = ma.access_id","where ma.menu_arrange_parent = '$parent_id' $group_id");
 while($row = mysqli_fetch_assoc($navQuery)){
  if($row['access_status'] === '0') continue; // exclude inactive access (continue iteration to next access)
  $navMenu[$row['menu_arrange_id']] = $row;
  $navChildMenu = getMenuArrange($row['menu_arrange_id'], "menu_arrange_order");
  if(count($navChildMenu) > 0)
   $navMenu[$row['menu_arrange_id']]['child'] = $navChildMenu;
 }
 usort($navMenu, sortMenuArrange($sort_menu_by));
 return $navMenu;
}

function getMenuGroup(){
  $navMenu = array();
  $navQuery = getData("*", "access_menu_group");
  while($row = mysqli_fetch_assoc($navQuery)){
    $navMenu[$row['menu_group_id']] = array(
      "name" => $row['menu_group_name'],
      "node" => getMenuArrange(0, 'menu_group_id', $row['menu_group_id'])
    );
  }
  return $navMenu;
}

function treeviewMenu($items, $root_el="<ul>{{item}}</ul>", $item_el="<li data-id='{{id}}'>{{name}} {{child}}</li>", $access_data=false, $append_child=""){
  $items_html = "";
  static $level = 0;
  $to_appendchild = str_repeat($append_child, $level);
  foreach($items as $item){
    if(isset($item['child'])){
      $level++;
      $child_html = treeviewMenu($item['child'], $root_el, $item_el, $access_data, $append_child);
    } else {
      $child_html = "";
    }
    $item_html = str_replace("{{id}}", isset($item['menu_arrange_id'])?$item['menu_arrange_id']:$item['access_id'], $item_el);
    $item_html = str_replace("{{name}}", $item['access_name']?:$item['menu_arrange_name'], $item_html);
    if($access_data){
      $item_html = str_replace("{{checked}}", in_array($item['access_id'], $access_data) ? "checked" : "", $item_html);
    }
    $item_html = str_replace("{{child}}", $child_html, $item_html);
    $item_html = str_replace("{{append_child}}", $to_appendchild, $item_html);
    $items_html .= $item_html;
  }
  $result_html = str_replace("{{item}}", $items_html, $root_el);
  $level = 0;
  return $result_html;
}

function update_arrange_order($data, $parent_id = 0){
  global $db;
  foreach($data as $order=>$val){
    $query = mysqli_query($db, "update access_menu_arrange set menu_arrange_order = '$order', menu_arrange_parent = '$parent_id' where menu_arrange_id='$val[id]'");
    if(isset($val['child'])){
      update_arrange_order($val['child'], $val['id']);
    }
  }
  return $query;
}
