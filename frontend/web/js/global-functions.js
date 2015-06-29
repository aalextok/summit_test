
function stoGetApiBaseUri(){
	return stoApiBaseUri;
}

function stoGetAuthToken(){
	return stoAuthToken;
	
}

function stoGetFrontendBaseUri(){
	return stoFrontendBaseUri;
	
}

function stoTimestampToIsoDate( msSinceEpoch ) {
	   var d = new Date( msSinceEpoch );
	   return d.getUTCFullYear() + '-' + (d.getUTCMonth() + 1) + '-' + d.getUTCDate() + 'T' +
	          d.getUTCHours() + ':' + d.getUTCMinutes() + ':' + d.getUTCSeconds();

}

function stoCretaeGoogleMaps( divId ) {
	var estonia = new google.maps.LatLng(58.605472,24.746704);
	var world = new google.maps.LatLng(41.2640069,-13.6730419);
	
	var myOptions = {
	    zoom: 2,
	    center: world,
	    mapTypeId: google.maps.MapTypeId.TERRAIN
	};
	
	return new google.maps.Map( document.getElementById( divId ) , myOptions);
}

