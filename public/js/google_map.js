document.body.addEventListener('DOMContentLoaded', function(){
	const input = document.getElementById('autocomplete');
	const hong_kong = { lat: 22.302711, lng: 114.177216 };
	const defaultBounds = {
		north: hong_kong.lat + 0.1,
		south: hong_kong.lat - 0.1,
		east: hong_kong.lng + 0.1,
		west: hong_kong.lng - 0.1,
	};
	const options = {
		bounds: defaultBounds,
		componentRestrictions: { country: 'hk' },
		fields: ['address_components', 'geometry', 'name'],
		strictBounds: false,
	};
	let autocomplete, map, place, lat, lng;
	
	document.getElementById('orderFormSubmit').addEventListener('click', function(event){
		var formData = new FormData(document.getElementById('orderForm'));
		formData.append('lat', lat);
		formData.append('lng', lng);
		return true;
	});
});
function initAutocomplete(){
	autocomplete = new google.maps.places.Autocomplete(input, options);
	autocomplete.addListener('place_changed', function(){
		place = autocomplete.getPlace();
		lat = place.geometry.location.lat();
		lng = place.geometry.location.lng();
	});
}


function initMap() {
	// The location of Hong Kong
	// The map, centered at Hong Kong
	map = new google.maps.Map(document.getElementById('map'),{zoom: 10, center: hong_kong,});
	// The marker, positioned at Hong Kong
	const marker = new google.maps.Marker({
		position: hong_kong,
		map: map,
	}); 
}

function initMix(){
	initMap();
	initAutocomplete();
}
