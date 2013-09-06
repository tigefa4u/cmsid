<?php 
/**
 * @fileName: mysql.php
 * @dir: libs/
 */
 
if(!defined('_iEXEC')) exit;

define( 'OBJECT', 'OBJECT', true );
define( 'OBJECT_K', 'OBJECT_K' );
define( 'ARRAY_A', 'ARRAY_A' );
define( 'ARRAY_N', 'ARRAY_N' );

/*
function get_query(){}
function get_result(){}
function get_num(){}
function get_id(){}
function get_free(){}
function get_row(){}
function get_obj(){}
function get_array(){}
function get_assoc(){}
function get_arrayfree(){}
function get_field(){}
function get_object(){}

function select_query(){}
function insert_query(){}
function update_query(){}
function replace_query(){}
function delete_query(){}
function truncate_query(){}
*/

class mysql {
	
	var $show_errors = true;
	
	var $real_escape = false;
	
	var $error = true;
	
	var $sql;
	
	var $field_types = array();
	
	var $old_tables = array( 'users', 'sidebar_action', 'sidebar', 'post_topic', 'post_comment_replay', 'post_comment', 'post', 'plugins', 'options', 'menu_sub','menu' );
	
	var $new_tables;
	
	var $last_result;
	
	function __construct( $dbuser, $dbpass, $dbname, $dbhost, $pre_table ) {
		register_shutdown_function( array( &$this, '__destruct' ) );
		
		$this->dbuser 	= $dbuser;
		$this->dbpass 	= $dbpass;
		$this->dbname 	= $dbname;
		$this->dbhost 	= $dbhost;
		$this->dbpre	= $pre_table;
		
		$this->connect_mysql();
	}
	
	function __destruct() {
		return true;
	}
	
	function __get( $v ) {
		$this->add_prefix($v);
		
		$this->$v = $this->set_prefix($v);
       	return $this->$v;
   	}
	
	function connect_mysql(){
		if ( debug ) {
			$this->dbh = mysql_connect( $this->dbhost, $this->dbuser, $this->dbpass, true );
		} else {
			$this->dbh = @mysql_connect( $this->dbhost, $this->dbuser, $this->dbpass, true );
		}

		if ( !$this->dbh ) {
			$this->bail( sprintf( "<h1>Kesalahan membangun koneksi database</h1>
			<p>Ini berarti bahwa username dan password informasi dalam file <code>iconfig.php</code> Anda tidak benar atau kami tidak dapat menghubungi server database di <code>%s</code>. Ini bisa berarti database server host Anda sedang down</p>
			<ul>
				<li>Apakah Anda yakin Anda memiliki username dan password yang benar?</li>
				<li>Apakah Anda yakin bahwa Anda telah mengetik nama host yang benar?</li>
				<li>Apakah Anda yakin bahwa database server berjalan?</li>
			</ul>
			<p>Jika Anda tidak yakin dengan istilah tersebut Anda mungkin harus menghubungi host Anda.</p>
			", $this->dbhost ), 'db_connect_fail' );

			return;
		}
		$this->select_db( $this->dbname, $this->dbh );
	}
	
	function select_db( $db, $dbh = null) {
		if ( is_null($dbh) )
			$dbh = $this->dbh;

		if ( !@mysql_select_db( $db, $dbh ) ) {
			
			$this->bail( sprintf( '<h1>Tidak dapat memilih database</h1>
			<p>Kami dapat terhubung ke server database (yang berarti nama pengguna dan kata sandi Anda oke) namun tidak dapat memilih database <code>%1$s</code> .</p>
			<ul>
			<li>Apakah Anda yakin itu ada?</li>
			<li>Pastikan file <code>config.php</code> dihapus pada saat melakukkan instalasi?</li>
			<li>Apakah <code>%2$s</code> memiliki izin untuk menggunakan database <code>%1$s</code>?</li>
			<li>Pada sebagian sistem nama database Anda diawali dengan nama pengguna Anda, sehingga akan menjadi seperti<code>username_%1$s</code>. Mungkinkah itu masalahnya?</li>
			</ul>
			<p>Jika Anda tidak tahu cara mengatur database <strong>Anda harus menghubungi host Anda</strong>.</p>', $db, $this->dbuser ), 'db_select_fail' );
			return;
		}
	}
	
	function mysql_version() {
		return preg_replace( '/[^0-9.].*/', '', mysql_get_server_info( $this->dbh ) );
	}
	
	function check_database_version(){
		global $version_system, $version_mysql;
		
		if ( version_compare($this->mysql_version(), $version_mysql, '<') )
			return new Error('database_version', sprintf( '<strong>ERROR</strong>: System %1$s requires MySQL %2$s or higher', $version_system, $version_mysql ));
	}
	
	function tables( $new_table, $prefix = true ) {
		
		if(!empty($new_table)) $tables = array_merge( (array)$new_table, (array)$this->old_tables );
		else $tables = $this->old_tables;
		
		if ( $prefix ) {
			
			foreach ( $tables as $k => $table ) {
				$tables[ $table ] = $this->dbpre . $table;
				unset( $tables[ $k ] );
			}

		}

		return $tables;
	}
	
	
	function add_table($new){
		if( !empty($new) && (string)$new ) 
			$this->new_tables = $new; 
	}
	/*
	 * $table = array('a','b');
	 * $db->add_table($table);
	 * echo $db->set_prefix('a');
	 * output : prefix_a
	 * script for update
	 **/
	
	function add_prefix($table){
		if(!empty($table)){
			if(!in_array($table,$this->old_tables) )			
			return $this->add_table($table);
		}
		
	}
	
	function set_prefix( $load_table ) {

		if ( $load_table ) {
			
			if(!$this->new_tables) $new_table = null;
			else $new_table = $this->new_tables;

			foreach($this->tables($new_table) as $k => $v) {
				if($load_table == $k) $load = $v;
			}
		}
		return $load;
	}
	
	function prepare( $query = null ) {
		if ( is_null( $query ) )
			return;
		$args 	= func_get_args();
		array_shift( $args );
		if ( isset( $args[0] ) && is_array($args[0]) )
		$args 	= $args[0];
		$query 	= str_replace( "'%s'", '%s', $query );
		$query 	= str_replace( '"%s"', '%s', $query );
		$query 	= preg_replace( '|(?<!%)%s|', "'%s'", $query );
		return @vsprintf( $query, $args );
	}
	/*
	* $db->query('select * from table_name');
	*
	*/
	function query( $query ){
		$this->sql = $query;
		if ( preg_match("/^\\s*(select|alter table|create|insert|delete|update|replace|truncate table|drop table|drop table if exists) /i", $this->sql ) ){
			$query = @mysql_query( $this->sql );
			return $query;
		}
	}	
	/*
	* $user_name='';
	* $user_pass='';
	*
	* $data = compact('user_name','user_pass');
	* $db->replace( 'table_name', $data + compact( 'user_login' ));
	* 
	*/
	
	function replace( $table, $data, $format = null ) {
		return $this->_ir( $table, $data, $format, 'REPLACE' );
	}	
	/*
	* $user_name='';
	* $user_pass='';
	*
	* $data = compact('user_name','user_pass');
	* $db->insert( 'table_name', $data + compact( 'user_login' ));
	* 
	*/
	function insert( $table, $data, $format = null ) {
		return $this->_ir( $table, $data, $format, 'INSERT' );
	}
	
	function _ir( $table, $data, $format = null, $type = 'INSERT' ) {
		$this->add_prefix($table);
		$table = $this->set_prefix($table);
		
		if ( ! in_array( strtoupper( $type ), array( 'REPLACE', 'INSERT' ) ) )
			return false;
		$formats = $format = (array) $format;
		$fields = array_keys( $data );
		$formatted_fields = array();
		foreach ( $fields as $field ) {
			if ( !empty( $format ) )
				$form = ( $form = array_shift( $formats ) ) ? $form : $format[0];
			elseif ( isset( $this->field_types[$field] ) )
				$form = $this->field_types[$field];
			else
				$form = '%s';
			$formatted_fields[] = escape( $form );
		}
		$sql = "{$type} INTO `$table` (`" . implode( '`,`', $fields ) . "`) VALUES ('" . implode( "','", $formatted_fields ) . "')";
		return $this->query( $this->prepare( $sql, $data ) );
	}
	/*
	* $user_name='';
	* $user_pass='';
	*
	* $data = compact('user_name','user_pass');
	* $db->update( 'table_name', $data, compact( 'user_login' ) );
	* 
	*/
	function update( $table, $data, $where, $format = null, $where_format = null ){
		$this->add_prefix($table);
		$table = $this->set_prefix($table);
		
		if ( ! is_array( $data ) || ! is_array( $where ) )
			return false;

		$formats = $format = (array) $format;
		$bits = $wheres = array();
		foreach ( (array) array_keys( $data ) as $field ) {
			if ( !empty( $format ) )
				$form = ( $form = array_shift( $formats ) ) ? $form : $format[0];
			elseif ( isset($this->field_types[$field]) )
				$form = $this->field_types[$field];
			else
				$form = '%s';
			$values   = escape( $form );
			$bits[]   = "`$field` = {$values}";
		}

		$where_formats = $where_format = (array) $where_format;
		foreach ( (array) array_keys( $where ) as $field ) {
			if ( !empty( $where_format ) )
				$form = ( $form = array_shift( $where_formats ) ) ? $form : $where_format[0];
			elseif ( isset( $this->field_types[$field] ) )
				$form = $this->field_types[$field];
			else
				$form = '%s';
			$values	  = escape( $form );
			$wheres[] = "`$field` = {$values}";
		}
		$sql = "UPDATE `$table` SET " . implode( ', ', $bits ) . ' WHERE ' . implode( ' AND ', $wheres );
		return $this->query( $this->prepare( $sql, array_merge( array_values( $data ), array_values( $where ) ) ) );
	}
	
	function select( $table, $where=false, $order=false ){
		$this->add_prefix($table);
		$table = $this->set_prefix($table);
		
		if(!$where){
			if($order){
			//order true
			$sql 	= "SELECT * FROM `$table` ".$order;
			}else{
			//order false & where false
			$sql 	= "SELECT * FROM `$table`";
			}
		}else{
			//where true
			if ( ! is_array( $where ) )
				return false;
				
			$wheres = array();
			foreach ( (array) $where as $field => $value) {
				if ( isset( $this->field_types[$field] ) )
					$form = $this->field_types[$field];
				else
					$form = $value;
				$wheres[] = $field."='".escape( $form )."'";
			}
			//if order true
			if($order)
			$sql 	= "SELECT * FROM `$table` WHERE " . implode( ' AND ', $wheres ) ." ".$order;			
			else
			$sql 	= "SELECT * FROM `$table` WHERE " . implode( ' AND ', $wheres );
			
		}
		return $this->query( $sql );
	}
	
	function delete( $table, $where=false){
		$this->add_prefix($table);
		$table = $this->set_prefix($table);
		
		//where true
		if ( ! is_array( $where ) )
			return false;
				
		$wheres = array();
		foreach ( (array) $where as $field => $value) {
			if ( isset( $this->field_types[$field] ) )
				$form = $this->field_types[$field];
			else
				$form = $value;
			$wheres[] = $field."='".escape( $form )."'";
		}
			
		$sql 	= "DELETE FROM `$table` WHERE " . implode( ' AND ', $wheres );
		return $this->query( $sql );
	}
	
	function truncate( $table ){
		$this->add_prefix($table);
		$table = $this->set_prefix($table);
		
		$sql 	= "TRUNCATE TABLE  `$table`";
		return $this->query( $sql );
	}
	
	function drop( $table ){
		$this->add_prefix($table);
		$table = $this->set_prefix($table);
		
		$sql 	= "DROP TABLE IF EXISTS `$table`";
		return $this->query( $sql );
	}
	
	function get_row( $sql = null, $output = OBJECT  ){
		
		if ( !isset( $sql ) )
			return null;

		if ( $output == OBJECT ) {
			return $sql ? $sql : null;
		} elseif ( $output == ARRAY_A ) {
			return $sql ? get_object_vars( $sql ) : null;
		} elseif ( $output == ARRAY_N ) {
			return $sql ? array_values( get_object_vars( $sql ) ) : null;
		} else {
			return false;
		}
	}
	
	function fetch_field( $sql = null ){
		if( $sql )
		return @mysql_fetch_field( $sql );
	}
	
	function fetch_free( $sql = null ){
		if( !$sql )
			return false;
			
		$ret = array();
		while ( $row = @mysql_fetch_array( $sql ) ) {
			$ret[] = $row;
		}
		$this->free( $sql ); 
		return $ret;
		
	}
	
	function fetch_array( $sql = null ){
		if( !$sql )	return false;
		else return @mysql_fetch_array( $sql );
	}
	
	function fetch_assoc( $sql = null ){
		if( !$sql )	return false;
		else return @mysql_fetch_assoc( $sql );
	}
	
	function fetch_row( $sql = null ){
		if( !$sql )	return false;
		else return @mysql_fetch_row( $sql );
	}
	
	function fetch_obj( $sql = null  ){
		if( !$sql ) return false;		
		else return @mysql_fetch_object( $sql );		
	}
	
	function row( $sql = null ){
		if( !$sql )
			return false;
			
		$ret = array();
		while ( $row = @mysql_fetch_row( $sql ) ) {
			$ret[] = $row;
		}
		$this->free( $sql ); 
		return $ret;
	}
	
	function num( $sql ){
		if( !$sql )
			return false;
			
		return @mysql_num_rows( $sql );
		
	}
	
	function num_query( $sql ){
		if( !$sql )
			return false;
			
		$sql = $this->query( $sql );
		return @mysql_num_rows( $sql );
		
	}
	
	function free( $sql ){
		if( !$sql )
			return false;
			
		return @mysql_free_result( $sql );		
	}
	
	function insert_id() {
		return mysql_insert_id();
	}
	
	
	function _real_escape( $string ) {
		if ( $this->connect() && $this->real_escape )
			return mysql_real_escape_string( $string, $this->dbh );
		else
			return addslashes( $string );
	}
	
	function weak_escape( $string ) {
		return addslashes( $string );
	}	
	
	function escape_map( $string ){
		if( !empty( $string ) ){
			if( !function_exists( 'mysql_real_escape_string' ) ){
				return array_map( 'mysql_escape_string', $string );
			}else{
				return array_map( 'mysql_real_escape_string', $string );
			}
		}
	}
	
	function escape_by_ref( &$string ) {
		$string = $this->_real_escape( $string );
	}
	
	function bail( $message, $error_code = '500' ) {
		if ( !$this->show_errors ) {
			if ( class_exists( 'Error' ) )
				$this->error = new Error($error_code, $message);
			else
				$this->error = $message;
			return false;
		}
		die($message);
	}
}