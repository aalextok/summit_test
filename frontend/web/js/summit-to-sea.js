jQuery( document ).ready(function() {
	
	stoInitLoginActions();
	stoInitVisitActions();
	
	/**
	 * If defined, modify content div styles
	 */
	if(jQuery('#main-style-replace').length > 0){
		jQuery("#main").attr( "style", jQuery('#main-style-replace').val() );
	}
	
});

function stoInitLoginActions(){
	
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

function stoInitVisitActions(){
	
	jQuery("#visit-add-btn").click(function(){
		jQuery(this).submit();
	});

	jQuery('#visit-add-btn input').keypress(function (e) {
	    if (e.which == 13) {
	    	jQuery(this).submit();
	    	return false;
	    }
	});
	
}


//http://stackoverflow.com/questions/14183025/setting-application-wide-http-headers-in-angularjs

var stsApp = angular.module('stsApp', []);





stsApp.controller('ChallengeListCtrl', function ($scope, $http) {
	//  ""
  $scope.competition = [];
  
  var config = {headers:  {
	      'Authorization': 'Bearer ' + stoGetAuthToken(),
	      'Accept': 'application/json;odata=verbose'
	  }
  };

  $http.get( stoGetApiBaseUri() + '/competition', config).success(function(data, status) {
	  	var baseUri = jQuery('#competition-view-base-uri').val();
	  	
	  	for(var i in data){
	  		var uriTmp = baseUri;
	  		uriTmp = uriTmp.replace("replaceid", data[i].id);
	  		data[i].uri = uriTmp;
	  	}
	  	
	    $scope.competition = data;
	    if(data.length > 0){
	    	jQuery("#challenges-no-items").hide();
	    } else {
	    	jQuery("#challenges-no-items").show();
	    }
  }).error(function(data, status) {
    // Some error occurred
	jQuery("#challenges-no-items h1").html("Challenges <br />failed to load");
  	jQuery("#challenges-no-items").show();
  });
  
  
  
});
