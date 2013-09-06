<?php 
/**
 * @fileName: manage.php
 * @dir: admin/manage/users
 */
if(!defined('_iEXEC')) exit;
global $db, $class_country, $widget;
$go 	= filter_txt($_GET['go']);
$act	= filter_txt($_GET['act']);
$to		= filter_txt($_GET['to']);
$offset	= filter_int($_GET['offset']);
$level	= filter_txt($_GET['level']);
$id  	= filter_txt($_GET['id']);

?>
<link href="admin/manage/users/style.css" rel="stylesheet" media="screen" type="text/css" />
<?php
echo js_redirec_list();

ob_start();

$header_title = 'Users Manager';

switch($go){
default:

if($act == 'del' && !empty($act)){
	del_users($id); 
	add_activity('manager_users',"menghapus user",'user');
}

$bt = 20;
$pg = (int) get_query_var('pg');
if(empty($pg)){
	$ps = 0;
	$pg = 1;
}
else{
	$ps = ($pg-1) * $bt;
}


if(isset($offset) && !empty($offset)) $limit = $offset;
else $limit	= 20;

if($level=='admin'){
	$add_query	= 'WHERE user_level="admin"';
	$sel_level1	= 'selected="selected"';
}elseif($level=='user'){
	$add_query	= 'WHERE user_level="user"';
	$sel_level2	= 'selected="selected"';
}elseif($level=='all'){
	$add_query	= 'WHERE user_level="user" OR  user_level="admin"';
	$sel_level3	= 'selected="selected"';
}else $add_query = '';

$sql_query = $db->query("SELECT * FROM $db->users $add_query ORDER BY user_registered DESC LIMIT $ps,$bt");
$total = $db->num_query("SELECT * FROM $db->users");

$warna = '';

if($offset==20){$sel1='selected="selected"';}
if($offset==30){$sel2='selected="selected"';}
if($offset==50){$sel3='selected="selected"';}
if($offset==100){$sel4='selected="selected"';}
?>
<div id="list-comment">
<?php
while($row = $db->fetch_array($sql_query)){
$warna  = empty ($warna) ? 'style="background:#f8f8f8;"' : '';

$avatar_img_profile = avatar_url($row['user_login']);
?>
<div class="comment" <?php echo $warna?>>
<img alt="" src="<?php echo $avatar_img_profile?>" class="avatar" height="50" width="50">
<div class="dashboard-comment-wrap">
<span class="name"><?php echo uc_first($row['user_login'])?></span><br />
Negara: <span class="country"><em><?php if(empty($row['user_country'])) echo 'unknow'; else echo $class_country->country_name($row['user_country']);?></em></span>
,Provinsi: <span class="prov"><em><?php if(empty($row['user_province'])) echo 'unknow'; else echo $row['user_province'];?></em></span><br /> 
Tanggal: <span class="date"><em><?php echo datetimes($row['user_registered'])?></em></span>	
			
<p class="row-actions">
<a href="?admin&sys=users&go=edit&to=data&id=<?php echo $row['ID']?>" class="button button2 l">Edit Data</a>
<a href="?admin&sys=users&go=edit&to=pass&id=<?php echo $row['ID']?>" class="button button2 m">Edit Password</a>
<a href="?admin&sys=users&act=del&id=<?php echo  $row['ID']?>"  onclick="return confirm('Are You sure delete this users?')" class="button button2 r">Trash</a>
</p>
<div style="clear:both"></div>
</div>
</div>
<?php
}
?>
</div>
<?php

$header_menu.= '<div class="header_menu_top">
<select onchange="redir(this)" name="show">
<option value="">-- Show --</option>
<option value="?admin&sys=users&offset=20" '.$sel1.'>20</option>
<option value="?admin&sys=users&offset=30" '.$sel2.'>30</option>
<option value="?admin&sys=users&offset=50" '.$sel3.'>50</option>
<option value="?admin&sys=users&offset=100" '.$sel4.'>100</option>
</select>
<select onchange="redir(this)" name="show">
<option value="">-- Level --</option>
<option value="?admin&sys=users&level=all" '.$sel_level3.'>All Level</option>
<option value="?admin&sys=users&level=admin" '.$sel_level1.'>Admin</option>
<option value="?admin&sys=users&level=user" '.$sel_level2.'>User</option>
</select></div>';
$header_menu = '<a href="?admin&sys=users&go=add" class="button button3">+ New</a>';

$sql_query_admin = $db->query("SELECT * FROM $db->users WHERE user_level='admin'");
$sql_query_user = $db->query("SELECT * FROM $db->users WHERE user_level='user'");

$paging = new Pagination();
$paging->set('urlscheme','?admin&sys=users&pg=%page%');
$paging->set('perpage',$bt);
$paging->set('page',$pg);
$paging->set('total',$total);
$paging->set('nexttext','Next');
$paging->set('prevtext','Previous');
$paging->set('focusedclass','selected');

$total_footer = 'Total Member : '.$db->num( $sql_query ).' Dari '.$db->num( $sql_query_admin ).' Administrator dan : '.$db->num( $sql_query_user ) .' User';

$footer = '<div class="left">'.$paging->display(true).'</div>';
$footer.= '<div class="right">'.$total_footer.'</div>';

break;
case'add':
echo '<div class="padding">';
if (isset($_POST['submit'])){
	$user_login			= filter_txt($_POST['username']);
	$user_email			= filter_txt($_POST['email']);
	$user_pass_new		= filter_txt($_POST['newpassword']);
	$user_pass_rep		= filter_txt($_POST['repassword']);
	$user_author		= filter_txt($_POST['author']);
	$user_sex			= filter_txt($_POST['sex']);
	$user_level			= filter_txt($_POST['level']);
	$user_status		= filter_int($_POST['status']);
	$user_country		= filter_txt($_POST['country']);
	
	$userdata = compact('user_login','user_pass_new','user_pass_rep','user_email','user_sex','user_author','user_status','user_level','user_country');
	add_member($userdata);
}
?>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td align="left"><label>User Name <strong>:</strong></label><br />
        <input type="text" name="username" value="" style="width:550px"></td>
      </tr>
    <tr>
      <td align="left"><label>E-Mail <strong>:</strong></label><br />
        <input type="text" name="email" value="" style="width:550px"></td>
      </tr>
    <tr>
      <td align="left"><label>Author Name <strong>:</strong></label><br />
        <input type="text" name="author" value="" style="width:550px"></td>
      </tr>
    <tr>
      <td align="left"><label>New Password <strong>:</strong></label><br />
        <input type="password" name="newpassword" value="" style="width:550px"></td>
      </tr>
    <tr>
      <td align="left"><label>Retry Password <strong>:</strong></label><br />
        <input type="password" name="repassword" value="" style="width:550px"></td>
      </tr>
    <tr>
      <td align="left"><label>Seorang <strong>:</strong></label><br />
        <select name="sex">
          <option value="p">Perempuan</option>
          <option value="l">Laki laki</option>
        </select></td>
      </tr>
    <tr>
      <td align="left"><label>Level <strong>:</strong></label><br />
       <select name="level">
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select></td>
      </tr>
    <tr>
      <td align="left"><label>Status <strong>:</strong></label> <br />       
      <select name="status">
          <option value="0">Inactive</option>
          <option value="1">Active</option>
        </select></td>
      </tr>
    <tr>
      <td align="left"><label>Country <strong>:</strong></label> <br />       
      <select name="country">
          <?php $class_country->country_list(); ?>
        </select></td>
      </tr>
  </table>
</div>
<?php
$header_menu = '<a href="?admin&sys=users" class="button button3 l"><span class="icon_head back">&laquo; Back</span></a>';
$header_menu.= '<div class="header_menu_top"><input type="submit" name="submit" class="button on m blue" value="Save"><input class="button r" type="reset" name="Reset" value="Reset"></div>';

break;
case'edit':
echo '<div class="padding">';
if($to == 'data'){

if (isset($_POST['submit'])){
	$user_login			= filter_txt($_POST['username']);
	$user_email			= filter_txt($_POST['email']);
	$user_author		= filter_txt($_POST['author']);
	$user_sex			= filter_txt($_POST['sex']);
	$user_level			= filter_txt($_POST['level']);
	$user_status		= filter_int($_POST['status']);
	$user_country		= filter_txt($_POST['country']);
	$user_province		= filter_txt($_POST['province']);
	
	$userdata = compact('user_login','user_email','user_sex','user_author','user_status','user_level','user_country','user_province');
	edit_member($userdata, $id );
}
$q	= $db->select("users",array('ID' => $id));
$r  = $db->fetch_array($q);
?>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td align="left"><label>User Name <strong>:</strong></label><br /> 
        <input type="text" name="username" value="<?php echo $r['user_login']?>" style="width:550px"></td>
      </tr>
    <tr>
      <td align="left"><label>E-Mail <strong>:</strong></label><br /> 
        <input type="text" name="email" value="<?php echo $r['user_email']?>" style="width:550px"></td>
      </tr>
    <tr>
      <td align="left"><label>Author Name <strong>:</strong></label><br /> 
        <input type="text" name="author" value="<?php echo $r['user_author']?>" style="width:550px"></td>
      </tr>
    <tr>
      <td align="left"><label>Seorang <strong>:</strong></label><br /> 
        <select name="sex">
      <?php
	  if( $r['user_sex'] == 'l' ):
	  ?>
        <option value="l" selected="selected">Laki laki</option>
        <option value="p">Perempuan</option>
      <?php
	  elseif( $r['user_sex'] == 'p' ):
	  ?>
        <option value="l">Laki laki</option>
        <option value="p" selected="selected">Perempuan</option>
      <?php
	  else:
	  ?>
        <option value="l">Laki laki</option>
        <option value="p">Perempuan</option>
      <?php
	  endif;
	  ?>
      </select></td>
      </tr>
    <tr>
      <td align="left"><label>Level <strong>:</strong></label><br /> 
       <select name="level">
      <?php if( $r['user_level'] == 'user' ): ?>
        <option value="user" selected="selected">User</option>
        <option value="admin">Admin</option>
      <?php elseif( $r['user_level'] == 'admin' ): ?>
        <option value="user">User</option>
        <option value="admin" selected="selected">Admin</option>
      <?php else: ?>
        <option value="user">User</option>
        <option value="admin">Admin</option>
      <?php endif;?>
      </select></td>
      </tr>
    <tr>
      <td align="left"><label>Status <strong>:</strong></label> <br />        
      <select name="status">
      <?php if( $r['user_status'] == 0 ): ?>
        <option value="0" selected="selected">Inactive</option>
        <option value="1">Active</option>
      <?php elseif( $r['user_status'] == 1 ): ?>
        <option value="0">Inactive</option>
        <option value="1" selected="selected">Active</option>
      <?php else: ?>
        <option value="0">Inactive</option>
        <option value="1">Active</option>
      <?php endif; ?>
      </select></td>
      </tr>
    <tr>
      <td align="left"><label>Negara <strong>:</strong></label> <br />        
      <select name="country">
          <?php $class_country->country_list($r['user_country']); ?>
        </select></td>
      </tr>
    <tr>
      <td align="left"><label>Provinsi <strong>:</strong></label> <br />       
      <input type="text" name="province" value="<?php echo $r['user_province']?>" style="width:550px;"></td>
      </tr>
  </table>
<?php
$header_menu = '<div class="header_menu_top"><input type="submit" name="submit" class="button on l blue" value="Save &amp; Update"><input class="button r" type="reset" name="Reset" value="Reset"></div>';

$header_menu.= '<a href="?admin&sys=users" class="button button3 l"><span class="icon_head back">&laquo; Back</span></a>';
$header_menu.= '<a href="?admin&sys=users&act=del&id='.$id.'" class="button button3 r red" onclick="return confirm(\'Are You sure delete this users?\')">Delete</a>';

}elseif($to == 'pass'){
	
if (isset($_POST['submit'])){
	$oldpass	= filter_txt($_POST['oldpass']);
	$newpass	= filter_txt($_POST['newpass']);
	$repass		= filter_txt($_POST['repass']);
	
	$data = compact('username','oldpass','newpass','repass') + array('user_id' => $id);
	change_password($data);	
	add_activity('manager_users',"mengubah kata sandi",'user');
}
?>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td align="left"><label>Old Password <strong>:</strong></label>
        <input type="password" name="oldpass" value="" style="width:550px;"></td>
      </tr>
    <tr>
      <td align="left"><label>New Password <strong>:</strong></label>
        <input type="password" name="newpass" value="" style="width:550px;"></td>
      </tr>
    <tr>
      <td align="left"><label>Re Password <strong>:</strong></label>
        <input type="password" name="repass" value="" style="width:550px;"></td>
      </tr>
  </table>
<?php
$header_menu = '<a href="?admin&sys=users" class="button button3 l"><span class="icon_head back">&laquo; Back</span></a>';
$header_menu.= '<div class="header_menu_top"><input type="submit" name="submit" class="button on m" value="Save &amp; Update"><input class="button r" type="reset" name="Reset" value="Reset"></div>';

}else{
	redirect('?admin&sys=users');
}
echo '</div>';
break;

}

$content = ob_get_contents();
ob_end_clean();

$form = 'name="form1" method="post" action=""';
add_templates_manage( $content, $header_title, $header_menu, null, $form, $footer ); 

?>