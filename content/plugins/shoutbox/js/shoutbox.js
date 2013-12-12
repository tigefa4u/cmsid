function submitShoutBox(objID){
	var valid 	= $('nama').present() && $('email').present() && $('pesan').present();
	var pattern = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
	if(!valid){
		alert('Semua form harus disini!');
		return;
	}
	if(!pattern.test($F('email'))){
		alert('Email not valid!');
		return;
	}	
	
	new Ajax.Request(objID+'&option=post',
		{
			method:'post',
			parameters:{
				nama:$F('nama'),
				email:$F('email'),
				pesan:$F('pesan')
			},
			onSuccess:function(){
				getShoutBoxList(objID);
				$('formShoutBox').reset();
				$('nama').focus();
			}
		}
	);
}
function getShoutBoxList(objID){
	new Ajax.Updater('divShoutBoxList',objID+'&option=get');
}
window.onload = function(){
	var obj = base_url + '/?request&load=shoutbox/data.php&plg=yes';
	$('formShoutBox').onsubmit = function(){
		submitShoutBox(obj); 
		return false;
	}
	getShoutBoxList(obj);
}
$(document).ready(function() { 
	$().ajaxStart(function() {
		$('#loading').show();
	}).ajaxStop(function() {
		$('#loading').hide();
	});
})
<!--
function check_length(my_form)
{
maxLen = 225;
if (my_form.pesan.value.length >= maxLen) {
var msg = "You have reached your maximum limit of characters allowed";
alert(msg);
my_form.pesan.value = my_form.pesan.value.substring(0, maxLen);
}
else{
my_form.text_num.value = maxLen - my_form.pesan.value.length;}
}
//-->