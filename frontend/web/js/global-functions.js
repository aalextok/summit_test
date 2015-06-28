
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
