
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


var openedInfowindowMarkerId = 0;
var rtMarkers = [];

function stoShowMarkers( $scope, markersData ) {
  //A simple example of how to achieve viewport marker management
  //var bounds = $scope.gmap.getBounds();
  // Call you server with ajax passing it the bounds
  // In the ajax callback delete the current markers and add new markers
  /*
   With the new list of markers you can remove the current markers (marker.setMap(null)) that are on the map and add the new ones (marker.setMap(map)).
   */
  
  //$scope.gmapMarkerCluster.setIgnoreHidden(true);
  
  $scope.gmapMarkerCluster.clearMarkers();
  rtMarkers = [];
  rtMarkers.lenght = 0;
  
  //items are loaded via inline script- global data/array 
  //markersData - add these onto map
  for (var i in markersData) {
    var md = markersData[i];
    var existsKey = getRtMarkersKeyIfMarkerAlreadyAdded( md.id );
    
    var latLng = new google.maps.LatLng(md.latitude, md.longtitude);
     
    if(existsKey < 0){
      var markernew = new google.maps.Marker({
        map: $scope.gmap,
        title: md.name,
        position: latLng
      });
      //add markers
      markernew.id = md.id;
      markernew.info = md.description;
      rtMarkers[i] = markernew;
      $scope.gmapMarkerCluster.addMarker( rtMarkers[i] );
      attachMarkerActions(rtMarkers[i]);
      
      if(openedInfowindowMarkerId == md.id){
        iwin.open($scope.gmap, rtMarkers[i]);
      }
      
    } else {
      //update marker
      rtMarkers[ existsKey ].setPosition( latLng );
    }
  }
  
  function attachMarkerActions(marker) {
    google.maps.event.addListener(marker, 'click', function() {
      iwin.setContent( marker.info );
      iwin.open($scope.gmap, marker);
      openedInfowindowMarkerId = marker.postId;
      return false;
    });
    google.maps.event.addListener(marker, 'mouseover', function() {
      iwin.setContent( marker.info );
      iwin.open($scope.gmap, marker);
      openedInfowindowMarkerId = marker.postId;
      return false;
    });
    
  }
  
  blockAddingDuringMarkerResetting = false;

}


function getRtMarkersKeyIfMarkerAlreadyAdded( pid ) {
  
  for(var i in rtMarkers){
    if(rtMarkers[i].postId == pid ){
      return i;
    }
  }
  
  return -1;
}

