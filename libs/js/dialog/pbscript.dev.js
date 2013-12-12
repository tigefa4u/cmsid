/*holds currently opened dialog*/
var XDLG="";
$(document).ready(function(){
	
	// SHOW a dialog
	jQuery.fn.showX = function () {	
		// display the boxes
		XDLG ="#"+$(this).attr("id");
		$("#redactor_modal_overlay").fadeIn();
		$(XDLG).show();	
		
		var height = this.outerHeight();
		var width = this.outerWidth();
									
		this.css({ 
			height: 'auto', 
			marginTop: '-' + (height+20)/2 + 'px', 
			marginLeft: '-' + (width/2) + 'px' 
		}).fadeIn('fast');
	}
	
	// CLOSE a dialog
	jQuery.fn.closeX = function () {
		$(this).hide();
		$("#redactor_modal_overlay").fadeOut();
		XDLG="";
	}
	
	// CLOSE on pressing `esc` button
	$(document).keyup(function(e){
		if(e.keyCode === 27) $(XDLG).closeX();
	});
	// CLOSE on clicking on request
	$("#redactor_modal,#redactor_modal_close").click(function(){
		$(XDLG).closeX();
	});

});	
