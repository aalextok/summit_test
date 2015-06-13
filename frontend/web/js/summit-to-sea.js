jQuery( document ).ready(function() {
	
	stsInitLoginActions();
	
});


function stsInitLoginActions(){
	
	jQuery("#front-login-btn").click(function(){
		jQuery(this).submit();
	})
}