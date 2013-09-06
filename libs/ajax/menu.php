<?php
/**
 * @file menu.php
 *
 */
//dilarang mengakses
if(!defined('_iEXEC')) exit;

global $login, $db;

if( 'libs/ajax/menu.php' == is_load_values() 
&& $login->check() 
&& $login->level('admin') ):

$_GET['aksi'] = !isset($_GET['aksi']) ? null : $_GET['aksi'];

switch($_GET['aksi']){
	default:
	case'add':
if (isset($_POST['title'])) {
	$data['title'] = esc_sql( trim($_POST['title']) );

	if (!empty($data['title'])) {
	$data['url'] = esc_sql( $_POST['url'] );
	$data['class'] = esc_sql( $_POST['class'] );
	$group_id = esc_sql( $_POST['group_id'] );
	$parent_id = esc_sql( $_POST['parent'] );
	$data['parent_id'] = $parent_id;
	$data['group_id'] = $group_id;
	$data['position'] = dynamic_menus_last_position($group_id, $parent_id) + 1;
	
	$querymax	= $db->query ("SELECT MAX(`position`) FROM `$db->menu` WHERE group_id = '$group_id' AND parent_id = '$parent_id'");
	$alhasil 	= $db->fetch_array($querymax);	
	$numbers	= $alhasil[0];

	if ( $db->insert('menu', $data) ) {
		$data['id'] = mysql_insert_id();
		$response['status'] = 1;
		$li_id = 'menu-'.$data['id'];
		$response['li'] = '<li id="'.$li_id.'" class="sortable_easymn">'.dynamic_menus_label($data, $numbers).'</li>';
		$response['li_id'] = $li_id;
	} else {
		$response['status'] = 2;
		$response['msg'] = 'Add menu error.';
	}
} else {
	$response['status'] = 3;
}

header('Content-type: application/json');
echo json_encode($response);

}
	break;
	case'edit':
	
if (isset($_GET['id'])) {
	
$id = esc_sql( (int)$_GET['id'] );
$data = dynamic_menus_row($id);

?>
<h2>Edit Menu</h2>
    <div class="padding">
<form method="post" action="?request&load=libs/ajax/menu.php&aksi=save">
		<label for="edit-menu-title">Title</label>
		<input type="text" name="title" id="edit-menu-title" value="<?php echo $data['title']; ?>">
		<label for="edit-menu-url">URL</label>
		<input type="text" name="url" id="edit-menu-url" value="<?php echo $data['url']; ?>">
		<label for="edit-menu-class">Class</label>
		<input type="text" name="class" id="edit-menu-class" value="<?php echo $data['class']; ?>">
        <?php echo dynamic_menus_box($data['group_id'], $id, $data['parent_id']);?>
	<input type="hidden" name="menu_id" value="<?php echo $data['id']; ?>">
</form>
</div>
<?php
		}
	break;
	case'save':
		if (isset($_POST['title'])) {
			$data['title'] = esc_sql( trim($_POST['title']) );
			if (!empty($data['title'])) {
				$data['id'] = esc_sql( $_POST['menu_id'] );
				$data['url'] = esc_sql( $_POST['url'] );
				$data['class'] = esc_sql( $_POST['class'] );
				$data['parent_id'] = esc_sql( $_POST['parent'] );
				if ( $db->update('menu', $data, array('id' => $data['id']) )) {
					$response['status'] = 1;
					$d['title'] = $data['title'];
					$d['url'] = $data['url'];
					$d['klass'] = $data['class']; //klass instead of class because of an error in js
					$response['menu'] = $d;
				} else {
					$response['status'] = 2;
					$response['msg'] = 'Edit menu error.';
				}
			} else {
				$response['status'] = 3;
			}
			header('Content-type: application/json');
			echo json_encode($response);
		}
	break;
	case'delete':
		if (isset($_POST['id'])) {
			$id = esc_sql( (int)$_POST['id'] );

			$ids = dynamic_menus_descendants($id);
			if (!empty($ids)) {
				$ids = implode(', ', $ids);
				$id = "$id, $ids";
			}

			$delete = $db->delete( 'menu', array('id' => $id));
			if ($delete) {
				$response['success'] = true;
			} else {
				$response['success'] = false;
			}
			header('Content-type: application/json');
			echo json_encode($response);
		}
	break;
	case'save_position':
		if (isset($_POST['dragbox_easymn'])) {
			$dragbox_easymn = $_POST['dragbox_easymn'];
			dynamic_menus_update_position(0, $dragbox_easymn);
		}
	break;
	
}

endif;