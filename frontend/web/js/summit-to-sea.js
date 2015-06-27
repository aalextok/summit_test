jQuery( document ).ready(function() {
	
	stoInitLoginAndRegisterActions();
	//Remove: stoInitVisitActions();
	
	/**
	 * If defined, modify content div styles
	 */
	if(jQuery('#main-style-replace').length > 0){
		jQuery("#main").attr( "style", jQuery('#main-style-replace').val() );
	}
	
});


function stoInitLoginAndRegisterActions(){
	
	jQuery("#front-login-btn").click(function(){
		//jQuery(this).submit();
		stoSubmitLoginForm();
		return false;
	});

	jQuery('#login-form input').keypress(function (e) {
	    if (e.which == 13) {
			stoSubmitLoginform();
	    	return false;
	    }
	});

	jQuery("#front-register-btn").click(function(){
		//jQuery(this).submit();
		stoSubmitRegisterForm();
		return false;
	});

	jQuery('#form-signup input').keypress(function (e) {
	    if (e.which == 13) {
			stoSubmitRegisterForm();
	    	return false;
	    }
	});

	//jQuery(".btn-facebook-login").click(function(){
	//	checkFacebookLoginState();
	//});
}

function stoSubmitLoginForm() {

	var username = jQuery('#loginEmail').val();
	var password = jQuery('#loginPassword').val();
	
	stoLoginToApiCall('web', 'login', '', '', username, password, '', 0);
}

function stoSubmitRegisterForm() {
	
	var username = jQuery('#signupform-username').val();
	var password = jQuery('#signupform-password').val();
	var firstname = jQuery('#signupform-firstname').val();
	var lastname = jQuery('#signupform-lastname').val();
	
	stoLoginToApiCall('web', 'register', firstname, lastname, username, password, '', 0);
}

function checkFacebookLoginState(){
	
	FB.getLoginStatus(function(response) {
		  if (response.status === 'connected') {
		    var uid = response.authResponse.userID;
		    var accessToken = response.authResponse.accessToken;
		    
		    stoLoginToApiCall('facebook', 'login', '', '', '', '', accessToken, 0);
		  } else if (response.status === 'not_authorized') {
		    FB.login(function(response) {
	    	   // handle the response
		    	checkFacebookLoginState();
		    }, {scope: 'email'});
		  } else {
			    FB.login(function(response) {
		    	   // handle the response
			    	checkFacebookLoginState();
			    }, {scope: 'email'});
		  }
	});
	
}


/**
 * Login/register trough API and get aut token to be used to login on frontend
 */
function stoLoginToApiCall( type, loginregister, firstname, lastname, username, password, token, cnt ){
	cnt = cnt + 1;//to prevent endless loop
	var inp = '';
	
	jQuery('.login-register-feedback').addClass('hidden');
	
	if(loginregister == 'login'){
		var url = stoGetApiBaseUri() + '/auth/login?auth_type=' + type;
		if( type == 'web' ){
			inp += 'email=' + username;
			inp += '&password=' + password;
		}
	} else {
		var url = stoGetApiBaseUri() + '/auth/signup?auth_type=' + type;
		if( type == 'web' ){
			inp += 'email=' + username;
			inp += '&password=' + password;
			inp += '&firstname=' + firstname;
			inp += '&lastname=' + lastname;
			inp += '&username=' + firstname + '.' + lastname;
		}
	}
	
    jQuery.ajax({
        type:"POST",
        beforeSend: function (request) {
            request.setRequestHeader("Authorization", "Bearer " + token);
        },
        url: url,
        data: inp,
        processData: false,
        success: function( data ) {
        	if( loginregister == 'register' && typeof data.user != 'undefined' ){
        		//successful creating, login now
        		stoLoginToApiCall( 'web', 'login', '', '', username, password, '', cnt );
        	}
        	
        	if( typeof data.user != 'undefined' && typeof data.auth_key != 'undefined' ){
            	stoLoginToFrontend( data.auth_key );
        	} else {
            	if(type == 'facebook' && loginregister == 'register' && cnt < 3){
            		stoLoginToApiCall( type, 'login', '', '', '', '', token, cnt );
            		console.log('Try to login after creating');
            	} else {
            		jQuery('.login-register-feedback').removeClass('hidden');
            	}
        	}
        },
        error: function( data ) {
        	//if user tried to login via FB, try registering beforehand, and login again
        	if(type == 'facebook' && loginregister == 'login' && cnt < 2){
        		stoLoginToApiCall( type, 'register', '', '', '', '', token, cnt );
        		console.log('Try to register after login try with FB');
        	} else {
        		jQuery('.login-register-feedback').removeClass('hidden');
        		if(typeof data.responseJSON.message != 'undefined'){
            		jQuery('.login-register-feedback .errors').html( data.responseJSON.message );
        		}
        	}
        },
        always: function( data ) {
        	//
        }
    });
    
}


/**
 * Login to frontend using obtained auth key
 */
function stoLoginToFrontend( token ){
	
	var url = stoGetFrontendBaseUri() + '/site/tokenlogin';
	
    jQuery.ajax({
        type:"POST",
        url: url,
        data: "loginToken=" + token,
        processData: false,
        success: function( data ) {
            var status = data.substr(0, 2);
            var url = data.substr(3);
            if(status == 'OK'){
            	document.location = url;
            } else {
            	//error
            }
        },
        error: function( data ) {
        	jQuery('.login-register-feedback').removeClass('hidden');
        },
        always: function( data ) {
        	//
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
  $scope.watchings = [];
  $scope.watchingsLoaded = false;
  
  var config = {headers:  {
	      'Authorization': 'Bearer ' + stoGetAuthToken(),
	      'Accept': 'application/json;odata=verbose'
	  }
  };
  
  $scope.searchChanged = function() { $scope.doSearch( $scope.searchQuery ); };
  
  $scope.doSearch = function( query ) {
	  jQuery("#users-list-loading").show();
	  
	  var data = Object();
	  data.page = 1;
	  data.firstname = query;
	  data.lastname = query;
	  data.username = query;
	  data.email = query;
	  data.phone = query;
	  
	  var dataString = jQuery.param(data);
	  
	  $http.get( stoGetApiBaseUri() + '/user/?' + dataString, config).success(function(data, status) {
		  	var baseUri = jQuery('#user-profile-view-base-uri').val();
		  	
		  	for(var i in data){
		  		var uriTmp = baseUri;
		  		uriTmp = uriTmp.replace("replaceid", data[i].id);
		  		data[i].uri = uriTmp;
		  		data[i].watching = false;
		  		data[i].watchingId = 0;
		  	}
	
		    $scope.users = data;
		    
		    if(!$scope.watchingsLoaded){
		    	$scope.loadWatchings();
		    } else {
		    	$scope.loadWatchings();
			    //$scope.applyWatchings(); - better load everytime ... in case user has changed some statuses somewhere
		    }
		    
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

  $scope.loadWatchings = function() {
	  $http.get( stoGetApiBaseUri() + '/watching', config).success(function(data, status) {
		  	console.log(data);

		    $scope.watchings = data;
		    $scope.watchingsLoaded = true;
		    $scope.applyWatchings();
	  }).error(function(data, status) {
		  
	  });
	  
  };

  $scope.applyWatchings = function(  ) {
	  for( var i in $scope.watchings ){
	  	for(var i2 in $scope.users){
	  		if($scope.watchings[i].watched_user_id == $scope.users[i2].id){
	  			$scope.users[i2].watching = true;
	  			$scope.users[i2].watchingId = $scope.watchings[i].id;
	  		}
	  	}
	  }
  }
  
  $scope.isWatchingClass = function( user ) {
	  if(user.watching){
		  return "unf-green user-unfollow";
	  }
	  
	  return "unf-green user-follow";
  }
  
  $scope.isWatchingText = function( user ) {
	  if(user.watching){
		  return "unfallow";
	  }
	  
	  return "follow";
  }
  
  /* this method is copied also 1:1 into users UserProfileCtrl controller */
  $scope.toggleWatching = function( oTarget, which, user ) {
      var elem = angular.element( oTarget.target );
      
      /**
       * On first click- init data for listing, which is already present in one profile view
       */
      if(which == 'list'){
          var dataId = elem.attr('data-id');
          var firstId = user.watching ? user.watchingId : user.id;

    	  if(dataId == 0){
        	  console.log("is listing- first init");
    		  elem.attr('data-id', firstId);
    		  elem.attr('data-user-id', user.id);
    		  elem.attr('data-state', (user.watching ? 1 : 0) );
    	  }
      }
      
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
			  
		  });
	  } else {
		  var url = stoGetApiBaseUri() + '/watching/delete/' + dataId;
		  
		  config.method = 'DELETE';
		  
		  /*
				$http({
				        method: 'DELETE', 
				        url: '/someUrl'
				});
		  */
		  $http['delete'](url, config).success(function(data, status) {
			  elem.text('follow');
			  elem.attr('data-state', 0);
			  elem.attr('data-id', dataOriginalUserId);
		  }).error(function(data, status) {
			  
		  });
	  }	  
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
  
  /* this method is copied also 1:1 into users UserSearchListCtrl controller */
  $scope.toggleWatching = function( oTarget, which, user ) {
      var elem = angular.element( oTarget.target );
      
      /**
       * On first click- init data for listing, which is already present in one profile view
       */
      if(which == 'list'){
          var dataId = elem.attr('data-id');
          var firstId = user.watching ? user.watchingId : user.id;

    	  if(dataId == 0){
        	  console.log("is listing- first init");
    		  elem.attr('data-id', firstId);
    		  elem.attr('data-user-id', user.id);
    		  elem.attr('data-state', (user.watching ? 1 : 0) );
    	  }
      }
      
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
			  
		  });
	  } else {
		  var url = stoGetApiBaseUri() + '/watching/delete/' + dataId;
		  
		  config.method = 'DELETE';
		  
		  /*
				$http({
				        method: 'DELETE', 
				        url: '/someUrl'
				});
		  */
		  $http['delete'](url, config).success(function(data, status) {
			  elem.text('follow');
			  elem.attr('data-state', 0);
			  elem.attr('data-id', dataOriginalUserId);
		  }).error(function(data, status) {
			  
		  });
	  }	  
  };

});



stsApp.controller('ProfileEditCtrl', function ($scope, $http) {
  
  var headers = {
      'Authorization': 'Bearer ' + stoGetAuthToken(),
      'Accept': 'application/json;odata=verbose',
      'Content-Type': 'application/x-www-form-urlencoded'
  };
  
  var profileUserId = jQuery('#user-profile-edit-user-id').val();
  var url = stoGetApiBaseUri() + '/user/update/' + profileUserId;
  
  $scope.formData = {};//gender

  $scope.proccessForm = function( oTarget ) {
      jQuery('.feedbacks .alert-success').addClass('hidden');
      jQuery('.feedbacks .alert-danger').addClass('hidden');
      jQuery('.feedbacks .ajax-content-loading').removeClass('hidden');
	  
      var tmpData = $scope.formData;
      if(tmpData.phone == ''){
    	  //delete tmpData.phone;
      }
      
	  $http({
		  method  : 'PUT',
		  url     : url,
		  data    : jQuery.param( tmpData ),
		  headers : headers
	  })
	  .success(function(data) {
	    if (data.id > 0) {
	      jQuery('.feedbacks .alert-success').removeClass('hidden');
	    } else {
	      jQuery('.feedbacks .alert-danger').removeClass('hidden');
	      jQuery('.feedbacks .alert-danger').html( data.message );
	    }
	    jQuery('.feedbacks .ajax-content-loading').addClass('hidden');	
	  })
	  .error(function(data) {
	      jQuery('.feedbacks .alert-danger').removeClass('hidden');
	      jQuery('.feedbacks .alert-danger').html( data.message );
	      jQuery('.feedbacks .ajax-content-loading').addClass('hidden');
	  });
	  
	  
  };

});


stsApp.controller('DashBoardCtrl', function ($scope, $http) {
  $scope.places = [];
  $scope.filterActivityId = 0;
  $scope.filterLocationId = 0;
  $scope.filterActivityName = "";
  $scope.filterLocationName = "";
  
  var config = {headers:  {
	      'Authorization': 'Bearer ' + stoGetAuthToken(),
	      'Accept': 'application/json;odata=verbose'
	  }
  };
  
  
  $scope.filterPlacesByActivity = function( oTarget, activityId, activityName ) {
	  //var elem = angular.element( oTarget.target );

	  var display = "Activity";
	  if(activityId > 0){
		  display = "Activity: " + activityName;
	  }
	  
	  jQuery('#activity-selector-selected').text( display );
	  $scope.filterActivityId = activityId;
	  $scope.filterActivityName = activityName;
	  $scope.doSearch( );
  }
  
  $scope.filterPlacesByLocation = function( oTarget, locationId, locationName ) {
	  //var elem = angular.element( oTarget.target );
	  
	  var display = "Location";
	  if(locationId > 0){
		  display = "Location: " + locationName;
	  }
	  
	  jQuery('#location-selector-selected').text( display );
	  $scope.filterLocationId = locationId;
	  $scope.filterLocationName = locationName;
	  $scope.doSearch( );
  }

  
  $scope.doSearch = function( ) {
	  jQuery("#places-list-loading").show();
  	  jQuery("#places-no-items").hide();
	  
	  var data = Object();
	  data.page = 1;
	  data.activity = $scope.filterActivityName;
	  data.location = $scope.filterLocationName;
	  
	  var dataString = jQuery.param(data);
	  
	  $http.get( stoGetApiBaseUri() + '/place/?' + dataString, config).success(function(data, status) {
		  	var baseUri = jQuery('#place-view-base-uri').val();
		  	
		  	for(var i in data){
		  		var uriTmp = baseUri;
		  		uriTmp = uriTmp.replace("replaceid", data[i].id);
		  		data[i].uri = uriTmp;
		  	}
	
		    $scope.places = data;
		    
		    if(data.length > 0){
		    	jQuery("#places-no-items").hide();
		      	jQuery("#places-list-loading").hide();
		    } else {
		    	jQuery("#places-no-items").show();
		      	jQuery("#places-list-loading").hide();
		    }
	  }).error(function(data, status) {
	  	jQuery("#places-no-items").show();
	  	jQuery("#places-list-loading").hide();
	  });
  };

  //initial search
  $scope.doSearch();
  
});


stsApp.controller('CompetitionViewCtrl', function ($scope, $http) {
  $scope.places = [];
  $scope.visits = [];
  
  
  var config = {headers:  {
	      'Authorization': 'Bearer ' + stoGetAuthToken(),
	      'Accept': 'application/json;odata=verbose'
	  }
  };

  $scope.isDone = function( place ) {
	  if(place.visited){
		  return "done";
	  }
	  
	  return "not-done";
  }
  
  $scope.isDoneMenu = function( place ) {
	  if(place.visited){
		  return "challenge-menu clearfix not-done";
	  }
	  
	  return "challenge-menu clearfix";
  }
  
  $scope.loadPlaces = function(  ) {
	  jQuery("#places-list-loading").show();
	  var cId = jQuery("#competition-view-id").val();
	  
	  $http.get( stoGetApiBaseUri() + '/competition/' + cId, config).success(function(data, status) {
		  	var baseUri = jQuery('#place-view-base-uri').val();
		  	
		  	for(var i in data.places){
		  		var uriTmp = baseUri;
		  		uriTmp = uriTmp.replace("replaceid", data.places[i].id);
		  		data.places[i].uri = uriTmp;
		  		data.places[i].visited = false;
		  	}
	
		    $scope.places = data.places;
		    
		    if(data.places.length > 0){
		    	jQuery("#places-no-items").hide();
		      	jQuery("#places-list-loading").hide();

		        $scope.loadVisits();
		    } else {
		    	jQuery("#places-no-items").show();
		      	jQuery("#places-list-loading").hide();
		    }
	  }).error(function(data, status) {
	  	jQuery("#places-list-no-items").show();
	  	jQuery("#places-list-loading").hide();
	  });
  };
  
  $scope.loadVisits = function(  ) {
	  //backend/web/visit
	  $http.get( stoGetApiBaseUri() + '/visit', config).success(function(data, status) {
		  	console.log(data);

		    $scope.visits = data;
		    $scope.applyVisits();
		    
	  }).error(function(data, status) {
		  
	  });
	  
  };

  $scope.applyVisits = function(  ) {
	  for( var i in $scope.visits ){
	  	for(var i2 in $scope.places){
	  		if($scope.visits[i].place_id == $scope.places[i2].id){
	  			$scope.places[i2].visited = true;
	  		}
	  	}
	  }
  }
  
  //initial loading
  $scope.loadPlaces();
});



stsApp.controller('PlaceVisitCtrl', function ($scope, $http) {

  var headers = {
      'Authorization': 'Bearer ' + stoGetAuthToken(),
      'Accept': 'application/json;odata=verbose',
      'Content-Type': 'application/x-www-form-urlencoded'
  };
  
  var profileUserId = jQuery('#user-profile-edit-user-id').val();
  var url = stoGetApiBaseUri() + '/visit/create';
  
  $scope.formData = {};
  //$scope.formData.activity_id = 1;

  $scope.proccessForm = function( oTarget ) {
      jQuery('.feedbacks .alert-success').addClass('hidden');
      jQuery('.feedbacks .alert-danger').addClass('hidden');
      jQuery('.feedbacks .ajax-content-loading').removeClass('hidden');
	  
      var tmpData = $scope.formData;
      
	  $http({
		  method  : 'POST',
		  url     : url,
		  data    : jQuery.param( tmpData ),
		  headers : headers
	  })
	  .success(function(data) {
	    if (data.id > 0) {
	      jQuery('.feedbacks .alert-success').removeClass('hidden');
	    } else {
	      jQuery('.feedbacks .alert-danger').removeClass('hidden');
	      jQuery('.feedbacks .alert-danger').html( data.message );
	    }
	    jQuery('.feedbacks .ajax-content-loading').addClass('hidden');	
	  })
	  .error(function(data) {
	      jQuery('.feedbacks .alert-danger').removeClass('hidden');
	      jQuery('.feedbacks .alert-danger').html( data.message );
	      jQuery('.feedbacks .ajax-content-loading').addClass('hidden');
	  });
	  
	  
  };

});


