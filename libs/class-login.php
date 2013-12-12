<?php 
/**
 * @fileName: class-login.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;

class login{
	protected $referal_login = '?login';
	protected $referal_admin = '?admin';
	
	protected $cookie_name = cookie_name;
	protected $cookie_time;
	
	public function __construct(){
		$this->cookie_time = (3600 * 24 * 30); // 30 days
		
		if(!$this->check() )
			$this->auto();
	}
	
	public function auto(){
		
		if( isset($this->cookie_name) ){
			// Check if the cookie exists
			if( isset($_COOKIE[$this->cookie_name]) ) {
				parse_str($_COOKIE[$this->cookie_name]);
				
				$username = esc_sql( $username );
				$password = esc_sql( $password );
			
				$rows = $this->data( array( 'user_login' => $username, 'user_pass' => $password ) );
				
				// Make a verification
				if(($username == $rows->user_login) && ($password == $rows->user_pass)){
					// Register the session
					$_SESSION['username'] = $rows->user_login;
					$_SESSION['password'] = $rows->user_pass;
					$_SESSION['level'] = $rows->user_level;
				}
			}
		}
	}	
	/*
	 * sign in 
	 * @param $data array
	 * return true|false
	 */
	function sign_in( $data ){
		extract($data, EXTR_SKIP);
		
		$user_login = esc_sql( $username );
		$user_pass  = esc_sql( $password );
		$rememberme = esc_sql( $remember );
		
		if( $rememberme == 1 ) $rememberme = 'on'; 
		else $rememberme = 'off';
		
		$data = compact('user_login', 'user_pass','rememberme');		
		$this->_in($data);
	}
	/*
	 * sign up 
	 * @param $data array
	 * return true|false
	 */
	function sign_up( $data ){
		extract($data, EXTR_SKIP);
		
		$user_login 	= esc_sql( $username );		
		$user_email 	= esc_sql( $email );		
		$password 		= esc_sql( $password );	
		$repassword 	= esc_sql( $repassword );		
		$user_sex 		= esc_sql( $sex );		
		$user_country 	= esc_sql( $country );
		$chekterm 		= esc_sql( $chekterm );
		
		$userdata = compact('user_login', 'user_email','password','repassword','user_sex','user_country','chekterm');
		$this->_up($userdata);
	}	
	/*
	 * activation key 
	 * @param $codeaktivasi string
	 * return echo message
	 */
	function activation($codeaktivasi){
		$key = esc_sql( $codeaktivasi );
		if( $this->_activation($key) )
			echo '<p class="message">Data telah dikirim ke email anda</p>';
		else 
			echo '<div id="error"><strong>ERROR</strong>: Gagal mengirim data ke email</div>';
	}
	
	function change_password( $data ){	
		extract($data, EXTR_SKIP);
		
		
		$user_login	= esc_sql( $this->exist_value('username') );
		$old_pass	= esc_sql( $old_pass );
		$new_pass	= esc_sql( $new_pass );
		$rep_pass	= esc_sql( $rep_pass );
		
		$oldpass	= md5($old_pass);
		$user_pass	= md5($rep_pass);
		
		if( empty($new_pass) || empty($rep_pass) ) $msg[] = '<strong>ERROR</strong>: New or RePassword is empty</a>';		
		if( $new_pass != $rep_pass ) $msg[] = '<strong>ERROR</strong>: Invalid New Password & RePassword not match</a>';
		
		$field = $this->data( compact('user_login') );
		if ($field->user_pass != $oldpass ) $msg[] = '<strong>ERROR</strong>: Invalid Old Password not match</a>';
		
		if( is_array($msg) ) 
		{
			foreach($msg as $val) echo '<div id="error">'.$val.' </div>';
		}
		
		if(empty($msg)){
			$update = $this->_change_password(compact('user_pass'),compact('user_login'));
			if( $update ) echo '<div id="success">The Password success to change</div>';
		}
	}	
	/*
	 * lost password
	 * @param $email string
	 * return echo message
	 */
	function lost_password($email){
		$user_email = esc_sql( $email );		
	
		if( empty( $user_email ) )
			$msg[] = '<strong>ERROR</strong>: The email field is empty.';
		else
		if( !valid_mail( $user_email ) ) 	
			$msg[] = '<strong>ERROR</strong>: The email not valid.';
			
		if( is_array($msg) )	{
			foreach($msg as $val)
				echo '<div id="error">'.$val.' </div>';
		}else{
				
		if( $this->_lost_password( $user_email ) )
			echo '<p class="message">Link aktivasi telah dikirim ke email anda, silahkan melakukan aktivasi</p>';
		else 
			echo '<div id="error"><strong>ERROR</strong>: Gagal mengirim aktivasi ke email</div>';
			
		}
	}	
	/*
	 * update user data
	 * @param $usredata
	 * return filter data for update
	 */
	function update_user($userdata){
		extract($userdata, EXTR_SKIP);
		
		$user_id 		= esc_sql( $user_id );
		$user_login 	= esc_sql( $username );
		$user_email 	= esc_sql( $email );
		$user_sex		= esc_sql( $sex );
		$user_author	= esc_sql( $author );
		$thumb			= esc_sql( $thumb );
		$user_country	= esc_sql( $country );
		$user_province	= esc_sql( $province );
		$user_url		= esc_sql( $website );
		
		$userdata = compact('user_login', 'user_email', 'user_sex','user_author', 'user_id','thumb','user_country','user_province','user_url');
		$this->_update_user($userdata);
	}
	/*
	 * Deactivate account
	 * @param $email string
	 * return echo message
	 */
	function deactivate_account(){
		global $db;
		$user_login	= esc_sql( $this->exist_value('username') );
		return $db->update( 'users', array('user_status' => 0), array('user_status' => 1,'user_login' => $user_login) );			
	}
	/*
	 * insert user data
	 * @param $userdata array
	 * return echo message
	 */
	function _in($data){
		global $db;
		
		extract($data, EXTR_SKIP);		
		$msg = $get_data_user = null;
		
		if( empty( $user_login ) ) 	
			$msg = 'Kolom username kosong.';
		elseif( empty( $user_pass ) ) 	
			$msg = 'Kolom sandi kosong.';
		elseif( $user_pass = md5($user_pass) ){
			$get_data_user = $this->get_data_user( compact('user_login','user_pass') );
		}
		
		if( empty($msg) && $get_data_user == null ){
			$msg = 'Nama pengguna atau sandi tidak valid. Klik di sini jika <a href="'.do_links('login',array('go'=>'lost')).'">Kehilangan kata sandi Anda?</a>';
		}
		
		if( !empty( $msg ) )
			printf('<div id="error"><strong>Kesalahan</strong>: %s </div>',$msg);
			
		if( empty($msg) && $get_data_user ){
			
			$data_compile = array_merge_simple( $get_data_user, array('rememberme' => $rememberme) );
			$save_log = $this->_log( $data_compile );
			if( $save_log ): 
			
				$redirect = true;
			
				$redirect_url = $this->referal_login;
				if( $this->exist_value('username') == 'admin' )
					$redirect_url = $this->referal_admin;
				
			endif;
		}
		
		if( $redirect ){
			echo '<div id="success">Redirect...</div>';
			redirect( $redirect_url );	
		}
		
	}		
	/*
	 * update data user
	 * @param $userdata array
	 * return echo message
	 */
	function _up($userdata){
		global $db;
		
		extract($userdata, EXTR_SKIP);				
		$msg = array();
		
		if( empty( $user_login ) ){
			$msg[] = 'The username field is empty.';
		}else{
			$field = $this->data( compact('user_login') );
			if($field->total > 0) $msg[] = 'Username "'.$user_login.'" sudah terpakai, silahkan ganti yg lain</a>';
		}
		
		
		if( empty( $user_email ) ) $msg[] = 'Kolom email kosong.';
		else{
			
			if( !valid_mail( $user_email ) ){	
				$msg[] = 'Email tidak valid.';
			}else{
				$field = $this->data( compact('user_email') );
				if( $field->total > 0) $msg[] = 'Email ini sudah pernah melakukkan registrasi</a>';
			}
		
		}
		
		if( empty( $password ) ){
			$msg[] = 'The password field is empty.';
		}elseif( $password != $repassword ){
			$msg[] = 'The password not match.';			
		}
				
		if( empty( $user_sex ) ) $msg[] = 'Kolom jenis kelamin belum dipilih.';		
		if( empty( $chekterm ) ) $msg[] = 'Peraturan belum dicentang.';
		
		if( $msg != null && is_array($msg))	{
			foreach($msg as $val){
				echo "<div id=\"error\"><strong>Salah</strong>: $val </div>";
			}
		}else{			
			$user_level 			= 'user'; // default
			$user_activation_key 	= random_password(20, false);
			$user_registered 		= date('Y-m-d H:i:s');
			$user_last_update 		= $user_registered;
			$user_pass 				= md5($repassword);
			$user_author 			= $user_login;
			$user_status			= 0;
			
			$data 		= compact('user_login','user_author','user_email','user_pass','user_sex','user_registered','user_last_update','user_status','user_country','user_activation_key');
			
			if( $db->insert( 'users', $data) ){
				
				$user_data = compact('user_email','user_activation_key');
				
				if( $this->message_activation($user_data) )
					echo '<div id="success">Anda berhasil menambahkan akun, cek email kamu untuk verifikasi</div>';
			}
		}
	}
	/*
	 * change password
	 * @param $data array
	 * @param $where array
	 * return true|false
	 */
	function _change_password( $data, $where ){	
		global $db;	
		
		$data = array_merge_simple( $data, array('user_last_update' => date('Y-m-d H:i:s') ) );
		return $db->update( 'users', $data, $where );
	}
	/*
	 * filtering activation
	 * @param $key string
	 * return echo message and log in
	 */
	function _activation($key){
		global $db;
		
		$msg = array();
		
		if( empty( $key ) ) 	
			$msg[] = '<strong>ERROR</strong>: The code activation field is empty.';
			
		$field = $this->data( array('user_activation_key' => $key) );
		
		if( $field->total < 1 ){ 	
			$msg[] = '<strong>ERROR</strong>: The code activation not valid.';
		}else{
			if( empty($msg) ):
			$new_pass			= random_password();
			$user_pass			= has_password($new_pass);
			$user_last_update 	= date('Y-m-d H:i:s');
			$user_status	 	= 1;
			
			$data = compact('user_pass','user_last_update','user_status');			
			$userupdate = $db->update( 'users', $data + array('user_activation_key' => ''), array('user_activation_key' => $key) );
			if( $userupdate ):
				
				$login 			= $field->user_login;
				$email 			= $field->user_email;
				$sex   			= $field->user_sex;
				$user_country   = $field->user_country;
				$user_province  = $field->user_province;
				
				if( $sex == 'l' ) $user_sex = 'Perempuan';
				elseif($sex == 'p') $user_sex = 'Laki - laki';
				else $user_sex = 'Unknow';
				
				if( empty($user_province) ) $user_province = 'Unknow (please change)';
				
				$user_data = compact('login','email','user_sex','new_pass','user_country','user_province');
				
				if( $this->message_reg($user_data) )
					$this->sign_in( array('username' => $login, 'password' => $new_pass, 'remember' => 'off') );
				
			endif;
			endif;
		}
		if( is_array($msg))	{
			foreach($msg as $val) echo '<div id="error">'.$val.' </div>';
		}
	}
	/*
	 * user filter dan update data
	 * @param $userdata array
	 * return echo message and redirect url
	 */
	function _update_user( $userdata ){
		global $db;
		extract($userdata, EXTR_SKIP);
		
		$ID = (int) $user_id;
		$user_last_update = date('Y-m-d H:i:s');
		
		$msg = array();
		if( empty($user_author) ) $msg[] = '<strong>Salah</strong>: Kolom nama kosong';
		if( empty( $user_email ) ) $msg[] = '<strong>Salah</strong>: Kolom email kosong.';
		elseif( !valid_mail( $user_email ) ) $msg[] = '<strong>Salah</strong>: Email tidak valid.';		
		
		if( empty($user_sex) )  $msg[] = '<strong>Salah</strong>: Kolom jenis kelamin belum dipilih';
		if( empty($user_country) ) $msg[] = '<strong>Salah</strong>: Kolom negara belum dipilih';
		
		if( is_array($msg) ){
			foreach($msg as $val) echo '<div id="error">'.$val.' </div>';
		}
		
		$field = $this->data( compact( 'ID' ) );
		
		if(!empty($thumb['name'])):
			$thumb	= hash_image( $thumb );
			$user_avatar = esc_sql($thumb['name']);
			//thumb extract		
			$thumb[name] = 'avatar_' . $thumb[name];
			$thumb[type] = $thumb[type];
			$thumb[tmp_name] = $thumb[tmp_name];
			$thumb[error] = $thumb[error];
			$thumb[size] = $thumb[size];
			
			upload_img_post($thumb,'',650,120);
			
			delete_img_post('avatar_'.$field->user_avatar);
		else:
			$user_avatar = esc_sql($field->user_avatar);
		endif;
		
		$data = compact('user_login','user_email','user_author','user_sex','user_last_update','user_country','user_province','user_url','user_avatar');
			
		if( $msg == null && $db->update( 'users', $data, compact( 'ID' ) ) ):
			echo '<div id="success">Berhasil memperbaharui akun</div>';
			redirect( '?' . $_SERVER['QUERY_STRING'] );
		endif;
	}
	
	/*
	 * filtering data lost password
	 * @param $user_mail string
	 * return echo message
	 */
	function _lost_password($user_email){
		global $db;
			
		$field = $this->data( compact('user_email') );
		
		if( $field->total < 1 ):	
			$msg[] = '<strong>ERROR</strong>: The email not registration.';
		else:
			if(empty($msg)):
			$user_activation_key 	= random_password(20, false);
			$user_last_update 		= date('Y-m-d H:i:s');
			
			$data = compact('user_last_update','user_activation_key');			
			$userupdate = $db->update( 'users', $data, compact('user_email') );
			if( $userupdate ):
				
				$user_data = compact('user_email','user_activation_key');
				$this->message_activation($user_data);
				
			endif;
			endif;
		endif;
		
		if( is_array($msg))	{
			foreach($msg as $val)
				echo '<div id="error">'.$val.' </div>';
		}
	}
	/*
	 * get data user
	 * @param $param_data array
	 * return array
	 */
	function get_data_user($param_data){
		extract($param_data, EXTR_SKIP);
		
		if( valid_mail($user_login) && $user_email = $user_login ){
			$where 	= compact('user_email','user_pass');
				
		}
		else
		{			
			$where 	= compact('user_login','user_pass');
		}
		
		$data_merge = array_merge_simple( $where, array('user_status'=>1) );
		$rows 		= $this->data( $data_merge );
		$data 		= array( 
			'user_login' 	=> $rows->user_login, 
			'user_level' 	=> $rows->user_level, 
			'user_pass' 	=> $rows->user_pass
		);
		
		if( $rows->total > 0 && is_array( $data ) ) 
			return $data;	
	}
	
	/*
	 * chekking log $_SESSION or $_COOKIE 
	 * @param $param string
	 * return true|false
	 */
	function exist( $param ){
		//memanggil session
		
		if( isset( $_SESSION[$param] ) ){
			$sess = esc_sql( $_SESSION[$param] );
			
			if( !empty( $sess ) )
				return $sess;	
		}
		
		return false;
	}	
	/*
	 * chekking value log $_SESSION atau $_COOKIE
	 * @param $param string
	 * return true|false
	 */
	function exist_value( $param ){
		//memanggil session
		
		if( isset( $_SESSION[$param] ) ){
			$sess = esc_sql( $_SESSION[$param] );
			
			if( !empty( $sess ) )
				return $sess;	
		}
		
		return false;
	}
	/*
	 * username cek 
	 * @param $data array
	 * return array
	 */
	function data( $data ){
		global $db;
		
		$retval = false;
		if( $query	= $db->select('users',$data) ):
			$retval = object_merge_simple( 
						$db->fetch_obj($query), 
						array( 
							'total' => $db->num($query) 
						) 
			);
		endif;
		
		return $retval;
	}
	/*
	 * function save_log()
	 * untuk menyimpan data user kedalam log baik cookie, session atau database
	 * using: $this->save_log($data)
	 */
	function _log( $data ){				
		extract($data, EXTR_SKIP);
		/*
		 * $session->set($param,$value)
		 * mulai mengeset session
		 */
		$_SESSION['username'] = esc_sql( $user_login );
		$_SESSION['password'] = esc_sql( $user_pass );
		$_SESSION['level'] = esc_sql( $user_level );
		$_SESSION['lw'] = 'single';
		
		if( $rememberme == 'on' ):
			
			setcookie (
				$this->cookie_name, 
				'username='.esc_sql( $user_login ).
				'&password='.esc_sql( $user_pass ).
				'&level='.esc_sql( $user_level ),
				time() + $this->cookie_time
			);	
			
		endif;			
		
		//memperbaharui data log pd database
		$this->_log_update($data);
		return true;
	}
	/*
	 * save log update data
	 * @param $data array
	 * return redirect url
	 */
	function _log_update($data){
		global $db;
		
		extract($data, EXTR_SKIP);
		$user_last_update = date('Y-m-d H:i:s');
			
		$db->update( 'users', compact( 'user_last_update' ), compact( 'user_login','user_level' ) );
	}
	/*
	 * log out
	 * 
	 * return string
	 */
	function login_out(){	
		
		if( isset( $_SESSION['username'] ) ){
			if( $this->_clear_log() )
			echo '<p class="message">Anda telah keluar dari website</p>';
		}
		
		redirect( $this->referal_login );
	}	
	function _clear_log(){
		unset( $_SESSION['username'] );
		unset( $_SESSION["password"] );
		unset( $_SESSION["level"] );
		unset( $_SESSION["lw"] );
			
		delete_directory( 'cache' );
			
		if(isset($_COOKIE[$this->cookie_name])){
			// remove 'site_auth' cookie
			setcookie ($this->cookie_name, '', time() - $this->cookie_time);
		}
	}
	/*
	 * chekking username
	 * return true|false
	 */
	function check(){	
		$user_login = $this->exist( "username" );		
		$field = $this->data( compact('user_login') );			
		if( $field->total > 0 && $field->user_status > 0 )
			return true;
		//else 
			//$this->_clear_log();
	}
	/*
	 * chekking lever user
	 * @param $param string
	 * return true|false
	 */
	function level( $param ){
		$level = $this->exist_value('level');
		if( $param == $level )
			return true;
	}
	/*
	 * send message activation
	 * @param $data array
	 * return echo message
	 */
	function message_activation($data){	
		extract($data, EXTR_SKIP);	
				
		$head  = 'Activation Registration<br><br>';
		$send .= '<strong>'.$head.'</strong><br>';
		$send .= 'Seseorang telah mendaftarkan akun email anda di <a href="'.site_url().'">'.site_url().'</a><br><br>';
		$send .= sprintf('Your Email: %s', $user_email) . "<br>";
		$send .= sprintf('Activation Code: %s', $user_activation_key) . "<br><br>";
		$send .= 'Masukkan code diatas melalui tautan ini : <a target="_blank" href="'.site_url('index.php?login&go=activation').'">'.site_url('index.php?login&go=activation').'"</a><br><br>';
		$send .= 'Atau<br><br>';
		$send .= 'Tautan aktivasi ini : <a target="_blank" href="'.site_url('index.php?login&go=activation&keys='.$user_activation_key).'">'.site_url('index.php?login&go=activation&keys='.$user_activation_key).'"</a><br><br>';
		$send .= 'Ini adalah email otomatis, diharapkan tidak membalas email ini<br>';
		
		if( mail_send($user_email, $head, $send) )
			echo '<p class="message">Link aktivasi telah dikirim ke email anda, silahkan melakukan aktivasi</p>';
		else 
			echo '<div id="error"><strong>ERROR</strong>: Gagal mengirim aktivasi ke email</div>';
			
		return true;
	}	
	/*
	 * send message registration member
	 * @param $data array
	 * return echo message
	 */
	function message_reg($data){	
		global $class_country;
		
		extract($data, EXTR_SKIP);	
		
		$head  = 'Login Data Registration<br><br>';
		$send .= '<strong>'.$head.'</strong><br>';
		$send .= 'Akun anda sudah diaktifkan berikut data datanya<br><br>';
		$send .= sprintf('Email: %s', $email) . "<br>";
		$send .= sprintf('User Name: %s', $login) . "<br>";
		$send .= sprintf('Password: %s', $new_pass) . "<br>";
		$send .= sprintf('Jenis Kelamin: %s', $user_sex) . "<br>";
		
		$user_country = $class_country->country_name($user_country);
		$send .= sprintf('Negara: %s', $user_country ) . "<br>";
		$send .= sprintf('Provinsi: %s', $user_province ) . "<br><br>";
		$send .= 'Silahkan kunjungi website : <a target="_blank" href="'.site_url('/?login&go=profile').'">'.site_url('/?login&go=profile').'"</a><br>dan ubah profile kamu<br><br>' ;
		$send .= 'Ini adalah email otomatis, diharapkan tidak membalas email ini<br>';
		
		if( mail_send($email, $head, $send) )
			echo '<p class="message">Akun dan Sandi telah dikirim ke email anda, silahkan login</p>';
		else 
			echo '<div id="error"><strong>ERROR</strong>: Gagal mengirim data akun ke email.</div>';
			
		return true;
	}
}