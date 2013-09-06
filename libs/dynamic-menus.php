<?php 
/**
 * @fileName: class-dynamic-menus.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;

function dynamic_menus($group_id, $attr = '', $ul = true) {	
	global $db;
	
	$group_id = esc_sql( $group_id );
	
	if( !class_exists('Tree') )
		include libs_path . '/class-tree.php';
		
	$tree = new Tree;
		
	$menu 	= array();
	$query 	= mysql_query("SELECT * FROM $db->menu WHERE group_id = '$group_id' ORDER BY parent_id, position");
	if( $db->num($query) > 0 )
	while( $row = mysql_fetch_assoc($query) ){
		$menu[] = $row;
	}
		
	foreach ($menu as $row) {

		$li_attr = '';
		if ($row['class']) {
			$li_attr = ' class="'.$row['class'].'"';
		}
			
		$label = '<a'.$li_attr.' href="'.site_url() . $row['url'].'">';
		$label .= $row['title'];
		$label .= '</a>';
			
		$tree->add_row($row['id'], $row['parent_id'], $li_attr, $label);
	}
	$menu = $tree->generate_list($attr, $ul);
	return $menu;
}

	
function dynamic_menus_group_title($group_id) {	
	global $db;
	
	$data 	= '';
	$group_id = esc_sql( $group_id );
	$query 	= mysql_query("SELECT title FROM $db->menu_group WHERE id = '$group_id'");
	$data 	= mysql_fetch_array($query);		
	return $data['title'];
}
	
function dynamic_menus_groups() {		
	global $db;
	
	$data = array();
	$query 	= mysql_query("SELECT id, title FROM $db->menu_group");
	while( $row = mysql_fetch_array($query) ){
		$data[] = array( 'id' => $row['id'],'title' => $row['title'] );
	}
		
	return $data;
}

function dynamic_menus_data($group_id) {
	global $db;
			
	$data 	= array();
	$group_id = esc_sql( $group_id );
	$query 	= mysql_query("SELECT * FROM $db->menu WHERE group_id = '$group_id' ORDER BY parent_id, position");
	
	while( $row = mysql_fetch_assoc($query) ) {
		$data[] = $row; 
	}	
	
	return $data; 
}

function dynamic_menus_label( $row, $numbers ) { 
		$label =
			'<div class="ns-row">' .
				'<div class="ns-title">'.$row['title'].'</div>' .
				'<div class="ns-url">'.limittxt($row['url'],6).'</div>' .
				'<div class="ns-class">'.$row['class'].'</div>' .
				'<div class="ns-actions">'.
					'<a href="#" class="edit" title="edit">edit</a>' .
					'<a href="#" class="delete" title="delete">delete</a>'.
					'<input type="hidden" name="menu_id" value="'.$row['id'].'">' .
				'</div>' .
				'<div class="ns-orders">';

					$ordering_down = '<a href="?admin&sys=appearance&go=menus&group_id='.$row['group_id'].'&parent_id='.$row['parent_id'].'&act=down&id='.$row['position'].'" class="down" title="down">down</a>';    
					$ordering_up = '<a href="?admin&sys=appearance&go=menus&group_id='.$row['group_id'].'&parent_id='.$row['parent_id'].'&act=up&id='.$row['position'].'" class="up" title="up">up</a>'; 		       
					
					if ($row['position'] == 1) $ordering_up = '';
					if ($row['position'] == $numbers) $ordering_down = '';
	
					$label.= $ordering_up.'  '.$ordering_down .
				'</div>' .
			'</div>';
		return $label;
}


	
function dynamic_menus_row($id) {
	global $db;
	
	$data 	= array();
	$id		= esc_sql( $id );
	$query 	= mysql_query("SELECT * FROM $db->menu WHERE id = '$id'");
	$data 	= mysql_fetch_assoc($query);
	return $data;
}
	
function dynamic_menus_box($group_id, $id = null, $parent = null){
	$id = esc_sql( $id );
	$group_id = esc_sql( $group_id );
	
	$get_menu = dynamic_menus_data($group_id);
	$retval = '<label for="edit-menu-select">Parent to</label><br>';
	$retval.= '<select id="edit-menu-select" name="parent">';	
	$retval.= '<option value="0">./</option>';	
	foreach($get_menu as $value){
		if( $value['id'] != $id && $id != $value['parent_id'] ){
				
		$selected = '';
		if( $value['id'] == $parent ) $selected = 'selected="selected"';
				
		$retval.= '<option value="'.$value['id'].'" '.$selected.'>./'.$value['title'].'</option>';
		}
	}
	$retval.= '</select>';
	return $retval;
}

function dynamic_menus_last_position($group_id, $parent_id) {
	global $db;
	
	$data  	= '';
	$group_id = esc_sql( $group_id );
	$parent_id = esc_sql( $parent_id );
	$query 	= mysql_query("SELECT MAX(position) FROM $db->menu WHERE group_id = '$group_id' AND parent_id = '$parent_id'");
	$data 	= mysql_result($query, 0);
	return $data;
}

function dynamic_menus_descendants($id) {
	class menus_descendants{
		public $ids = array();			
		public function dynamic_menus_descendants($id) {
			global $db;
			
			$id = esc_sql( $id );
			$query 	= mysql_query("SELECT id FROM $db->menu WHERE parent_id = '$id'");
			while( $row = mysql_fetch_row($query)){
				$data[] = $row[0];
			}
		
			if (!empty($data)) {
				foreach ($data as $v) {
					$this->ids[] = $v;
					$this->dynamic_menus_descendants($v);
				}
			}
		}
	}	
	
	$descendants = new menus_descendants;
	$descendants->dynamic_menus_descendants($id);
		
	return $descendants->ids;
}

function dynamic_menus_update_position($parent, $children) {
	global $db;
	$i = 1;
	foreach ($children as $k => $v) {
		$id = (int)$children[$k]['id'];
		$data['parent_id'] = $parent;
		$data['position'] = $i;
		$db->update('menu', $data, array('id' => $id) );
		if (isset($children[$k]['children'][0])) {
			dynamic_menus_update_position($id, $children[$k]['children']);
		}
	$i++;
	}
}

function current_menu_group($group_title,$group_id) { 
	
	$content = '<div class="padding"><span id="edit-group-input">'.$group_title.'</span>
	(ID: <b>'.$group_id.'</b>)
	<div>
	<a id="edit-group" href="#">Edit</a>';
	if ($group_id > 2) : 
		$content .= '&middot; <a id="delete-group" href="#">Delete</a>';
	endif;
		$content .= '</div></div>';
	return $content;
} 

function add_menu_on_group($group_id) { 
	$content ='<div class="padding">
    <form id="form-add-menu" method="post" action="?request&load=libs/ajax/menu.php&aksi=add">
	<label for="menu-title">Title</label>
	<input type="text" name="title" id="menu-title" style="width:95%">
	<label for="menu-url">URL</label>
	<input type="text" name="url" id="menu-url" style="width:95%">
	<label for="menu-class">Class</label>
	<input type="text" name="class" id="menu-class" style="width:95%">
	'.dynamic_menus_box($group_id).'
	<p class="buttons">
	<input type="hidden" name="group_id" value="'.$group_id.'">
	<input id="add-menu" type="submit" class="button" value="Add Menu">
	</p>
	</form></div>';
	return $content;
} 