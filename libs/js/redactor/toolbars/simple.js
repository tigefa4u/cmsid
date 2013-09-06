if (typeof RTOOLBAR == 'undefined') var RTOOLBAR = {};

RTOOLBAR['simple'] = 
{
	
	bold:
	{ 
		title: RLANG.bold,
		exec: 'Bold',
	 	param: false	
	},
	italic:
	{
		title: RLANG.italic,
		exec: 'italic',
	 	param: null
	},
	fontcolor:
	{
		title: RLANG.fontcolor, 
		func: 'show'
	},	
	backcolor:
	{
		title: RLANG.backcolor, 
		func: 'show'
	},
	insertunorderedlist:
	{
		title: '&bull; ' + RLANG.unorderedlist,
		exec: 'insertunorderedlist',
	 	param: null
	},
	insertorderedlist:
	{
		title: '1. ' + RLANG.orderedlist,
		exec: 'insertorderedlist',	
	 	param: null
	},
	justifyleft:
	{	
		exec: 'JustifyLeft', 
		name: 'JustifyLeft', 
		title: RLANG.align_left
	},					
	justifycenter:
	{
		exec: 'JustifyCenter', 
		name: 'JustifyCenter', 
		title: RLANG.align_center
	},
	justifyright: 
	{
		exec: 'JustifyRight', 
		name: 'JustifyRight', 
		title: RLANG.align_right
	},	
	justify: 
	{
		exec: 'justifyfull', 
		name: 'justifyfull', 
		title: RLANG.align_justify
	},	
	horizontalrule: 
	{
		exec: 'inserthorizontalrule', 
		name: 'horizontalrule', 
		title: RLANG.horizontalrule
	}
};