<?php 
/**
 * @fileName: class-error.php
 * @dir: libs/
 */
if(!defined('_iEXEC')) exit;

class Error {
	
	/** menyimpan daftar kesalahan*/
	var $errors = array();
	
	/** menyimpan daftar data kesalahan*/
	var $error_data = array();
	
	/**
	 * mengeset pesan kesalahan
	 *
	 * @param string|int $code
	 * @param string $message
	 * @param mixed $data
	 
	 * @return Error
	 */
	function __construct($code = '', $message = '', $data = '') {
		if ( empty($code) )
			return;

		$this->errors[$code][] = $message;

		if ( ! empty($data) )
			$this->error_data[$code] = $data;
	}
	
	/**
	 * memanggil semua kode kesalahan
	 *
	 * @access public
	 *
	 * @return array
	 */
	function get_error_codes() {
		if ( empty($this->errors) )
			return array();

		return array_keys($this->errors);
	}	

	/**
	 * Panggil kode kesalahan yang pertama jika tersedia
	 *
	 * @return string|int string kosong, jika tidak tersedia
	 */
	function get_error_code() {
		$codes = $this->get_error_codes();

		if ( empty($codes) )
			return '';

		return $codes[0];
	}
	
	/**
	 * Panggila semua kode pesan kesalahan atau pesan kesalahan yang sama
	 *
	 * @param string|int $code
	 * @return array or array kosong
	 */
	function get_error_messages($code = '') {
		// Return all messages if no code specified.
		if ( empty($code) ) {
			$all_messages = array();
			foreach ( (array) $this->errors as $code => $messages )
				$all_messages = array_merge($all_messages, $messages);

			return $all_messages;
		}

		if ( isset($this->errors[$code]) )
			return $this->errors[$code];
		else
			return array();
	}
	
	/**
	 * Panggil pesan kesalahan kesatuan
	 *
	 * @param string|int $code
	 * @return string
	 */
	function get_error_message($code = '') {
		if ( empty($code) )
			$code = $this->get_error_code();
		$messages = $this->get_error_messages($code);
		if ( empty($messages) )
			return '';
		return $messages[0];
	}
	
	/**
	 * Panggil data kesalahan atau kode kesalahan
	 *
	 * @param string|int $code
	 * @return mixed null, jika tidak ada kesalahan
	 */
	function get_error_data($code = '') {
		if ( empty($code) )
			$code = $this->get_error_code();

		if ( isset($this->error_data[$code]) )
			return $this->error_data[$code];
		return null;
	}
	
	/**
	 * Menambahkan pesan kesalahan pada daftar kesalahan
	 *
	 * @param string|int $code
	 * @param string $message
	 * @param mixed $data
	 */
	function add($code, $message, $data = '') {
		$this->errors[$code][] = $message;
		if ( ! empty($data) )
			$this->error_data[$code] = $data;
	}
	
	/**
	 * Tambahkan kode data kesalahan
	 *
	 * @param mixed $data
	 * @param string|int $code
	 */
	function add_data($data, $code = '') {
		if ( empty($code) )
			$code = $this->get_error_code();

		$this->error_data[$code] = $data;
	}
}

	/**
	 * mengecek kesalahan
	 *
	 * @param mixed $thing
	 * @return bool true
	 */
	function is_error($thing) {
		if ( is_object($thing) && is_a($thing, 'Error') )
			return true;
		return false;
	}