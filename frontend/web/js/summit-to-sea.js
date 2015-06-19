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




stsApp.controller('UserSearchListCtrl', function ($scope, $http) {
  $scope.users = [];
  
  var config = {headers:  {
	      'Authorization': 'Bearer ' + stoGetAuthToken(),
	      'Accept': 'application/json;odata=verbose'
	  }
  };
  
  $scope.searchChanged = function() { $scope.doSearch( $scope.searchQuery ); };
  
  $scope.doSearch = function( query ) {
	  console.log( "Searching " + query );
	  jQuery("#users-list-loading").show();	
	  $http.get( stoGetApiBaseUri() + '/user', config).success(function(data, status) {
		  	var baseUri = jQuery('#user-profile-view-base-uri').val();
		  	
		  	for(var i in data){
		  		var uriTmp = baseUri;
		  		uriTmp = uriTmp.replace("replaceid", data[i].id);
		  		data[i].uri = uriTmp;
		  	}
	
		    $scope.users = data;
		    if(data.length > 0){
		    	jQuery("#users-list-no-items").hide();
		      	jQuery("#users-list-loading").hide();
		    } else {
		    	jQuery("#users-list-no-items").show();
		      	jQuery("#users-list-loading").hide();
		    }
	  }).error(function(data, status) {
	  	jQuery("#users-list-no-items").show();
	  	jQuery("#users-list-loading").hide();
	  });
  };
  
  //initial search
  $scope.doSearch("");
  
});



stsApp.controller('UserProfileCtrl', function ($scope, $http) {
  
  var config = {headers:  {
	      'Authorization': 'Bearer ' + stoGetAuthToken(),
	      'Accept': 'application/json;odata=verbose'
	  }
  };
  
  $scope.toggleWatching = function( oTarget ) {
      var elem = angular.element( oTarget.target );
      
      var state = elem.attr('data-state');
      var dataId = elem.attr('data-id');
      var dataOriginalUserId = elem.attr('data-user-id');

	  console.log( state + ':' + dataId);
	  var data = Object();
	  
	  if(state < 1){
		  var url = stoGetApiBaseUri() + '/watching/create';
		  data.watched_user_id = dataId;
		  
		  $http.post( url, data, config).success(function(data, status) {
			  elem.text('unfollow');
			  elem.attr('data-state', 1);
			  elem.attr('data-id', data.id);
		  }).error(function(data, status) {
			  alert(status);
		  });
	  } else {
		  var url = stoGetApiBaseUri() + '/watching/delete/' + dataId;
		  
		  $http.delete( url, config).success(function(data, status) {
			  elem.text('follow');
			  elem.attr('data-state', 0);
			  elem.attr('data-id', dataOriginalUserId);
		  }).error(function(data, status) {
			  alert(status);
		  });
	  }
	  
	  
  };
  
  
});





