<script>
function initAutocomplete() {
var map = new google.maps.Map(document.getElementById('map'), {
center: {lat: -33.8688, lng: 151.2195},
zoom: 13,
mapTypeId: 'roadmap'
});

// Create the search box and link it to the UI element.
var ar_input = document.getElementById('ar_name');
var searchBox = new google.maps.places.SearchBox(ar_input);
var en_input = document.getElementById('en_name');
var searchBox = new google.maps.places.SearchBox(en_input);

// Bias the SearchBox results towards current map's viewport.
map.addListener('bounds_changed', function () {
searchBox.setBounds(map.getBounds());
});

var markers;
// Listen for the event fired when the user selects a prediction and retrieve
// more details for that place.
searchBox.addListener('places_changed', function () {
if(markers)
{
markers.setMap(null);
}
var places = searchBox.getPlaces();

// Clear out the old markers.


// For each place, get the icon, name and location.
var bounds = new google.maps.LatLngBounds();

// For each place, get the icon, name and location.
var bounds = new google.maps.LatLngBounds();
places.forEach(function (place) {
if (!place.geometry) {
console.log("Returned place contains no geometry");
return;
}
var icon = {
url: place.icon,
size: new google.maps.Size(71, 71),
origin: new google.maps.Point(0, 0),
anchor: new google.maps.Point(17, 34),
scaledSize: new google.maps.Size(25, 25)
};

// Create a marker for each place.
markers=new google.maps.Marker({
map: map,
icon: icon,
title: place.name,
position: place.geometry.location
});
if (place.geometry.viewport) {
// Only geocodes have viewport.
bounds.union(place.geometry.viewport);
} else {
bounds.extend(place.geometry.location);
}
});
google.maps.event.addListener(map, 'click', function (event) {
//call function to create marker

if(markers)
{
markers.setMap(null);
var myLatLng = event.latLng;
}

markers = new google.maps.Marker({
position: myLatLng,
map: map,

});
map.fitBounds(bounds);
console.log(myLatLng.lat());
})
// Information for popup window if you so chose to have one
/*
marker = createMarker(event.latLng, "name", "<b>Location</b><br>"+event.latLng);
*/


map.fitBounds(bounds);
});
}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBw_c3WtzhmErH2iIi8EpjTztd8zKVTzkY&libraries=places&callback=initAutocomplete"
async defer></script>