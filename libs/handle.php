<?php

function log_exception($type, $message, $file, $line)
{
	$bail = sprintf( "<h1>Exception Occured</h1>
			<p>Telah terjadi kesalahan kode berikut:</p>
			<ul>
				<li>Tipe: %s</li>
				<li>Pesan: %s</li>
				<li>File: %s</li>
				<li>Baris: %s</li>
			</ul>
			<p>Jika Anda tidak yakin dengan istilah tersebut Anda mungkin harus menghubungi pihak pengembang.</p>
			", $type, $message, $file, $line);
	die( $bail );
	exit();
}

//create your error handler function
function handler($error_type, $error_message, $error_file, $error_line)
{
	switch($error_type){
		case E_ERROR:
		case E_CORE_ERROR:
		case E_COMPILE_ERROR:
		case E_PARSE:
			//fatal
			log_exception('FATAL ' . $error_type, $error_message, $error_file, $error_line);
			break;
		case E_USER_ERROR:
		case E_RECOVERABLE_ERROR:
			//error
			log_exception('ERROR ' . $error_type, $error_message, $error_file, $error_line);
			break;
		//case E_WARNING:
		case E_CORE_WARNING:
		case E_COMPILE_WARNING:
		case E_USER_WARNING:
			//warn
			log_exception('WARN ' . $error_type, $error_message, $error_file, $error_line);
			break;
		case E_STRICT:
			//debug
			break;
		case E_NOTICE:
		case E_USER_NOTICE:
			//info
			break;			
		default: 
			//warn
	}
}
function shutdown_handler(){
	$lasterror = error_get_last();
	switch ($lasterror['type'])
	{
		case E_ERROR:
		case E_CORE_ERROR:
		case E_COMPILE_ERROR:
		case E_USER_ERROR:
		case E_RECOVERABLE_ERROR:
		case E_CORE_WARNING:
		case E_COMPILE_WARNING:
		case E_PARSE:
			log_exception($lasterror['type'], $lasterror['message'], $lasterror['file'], $lasterror['line']);
	}
}
/*
set error handling to 0
we will handle all error reporting
only notifying admin on warnings and fatal errors
don't bother with notices as they are trivial errors
really only meant for debugging
*/
error_reporting(0);

//set the error handler to be used
set_error_handler("handler");
register_shutdown_function("shutdown_handler");

/*
Create the rest of your page here.
We will not be displaying any errors
We will be e-mailing the admin an error message
Keep in mind that fatal errors will still halt the
execution, but they will still notify the admin
*/
?>
