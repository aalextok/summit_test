jQuery( document ).ready(function() {
	
	stsInitLoginActions();
	
});


function stsInitLoginActions(){
	
	jQuery("#front-login-btn").click(function(){
		jQuery(this).submit();
	});

	jQuery('#login-form input').keypress(function (e) {
	    if (e.which == 13) {
	    	jQuery(this).submit();
	    	return false;
	    }
	});
	
}