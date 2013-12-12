<?php
/**
 * @file menu.php
 *
 */
//dilarang mengakses
if(!defined('_iEXEC')) exit;

global $login, $db;

if('libs/ajax/menu-group.php' == is_load_values() 
&& $login->check() 
&& $login->level('admin') ):

$_GET['aksi'] = !isset($_GET['aksi']) ? null : $_GET['aksi'];

switch($_GET['aksi']){
	case'add':
	
	if (isset($_POST['title'])) {
	$data['title'] = trim($_POST['title']);
	
	if (!empty($data['title'])) {
		if ( $db->insert('menu_group', $data) ) {
		$response['status'] = 1;
		$response['id'] = mysql_insert_id();
		} else {
		$response['status'] = 2;
		$response['msg'] = 'Add menu group error.';
		}
	} else {
		$response['status'] = 3;
	}
	header('Content-type: application/json');
	
	echo json_encode($response);
	} else {
	?>
	<h2>Add Menu Group</h2>    
    <div class="padding">
	<form method="post" action="<?php echo site_url('?request&load=libs/ajax/menu-group.php&aksi=add');?>">
			<label for="menu-group-title">Group Title</label>
			<input type="text" name="title" id="menu-group-title">
	</form>
    </div>
	<?php
	}
	
	break;
	case'edit':
	
	if (isset($_POST['title'])) {
	$id = (int)$_POST['id'];
	$data['title'] = trim($_POST['title']);
	$response['success'] = false;
	
	if ( $db->update('menu_group', $data, array('id' => $id) ))
		$response['success'] = true;
	
	header('Content-type: application/json');
	echo json_encode($response);
	}
		
	break;
	case'delete':
	
	if (isset($_POST['id'])) {
	$id = (int)$_POST['id'];
		if ($id == 1) {
			$response['success'] = false;
			$response['msg'] = 'Cannot delete Group ID = 1';
		} else {
			$delete = $db->delete( 'menu_group', array('id' => $id));
			if ($delete) {
				$db->delete( 'menu', array('group_id' => $id));
				$response['success'] = true;
			} else {
				$response['success'] = false;
			}
		}
		
	header('Content-type: application/json');
	echo json_encode($response);
	
	}
	
	break;
	
}

endif;