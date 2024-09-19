<h1>Google Map Here</h1>
<div id="map" style="height: 400px; width: 100%;"></div>

<input id="search_input" type="text" placeholder="Search for places" />


<script async defer
        src="https://maps.google.com/maps/api/js?libraries=drawing,places&key={{ env('GOOGLE_MAP_KEY') }}&callback=initializeMap">
</script>

<script>
    var latitude = 30.0692019;
    var longitude = 31.3055374;
    var map;
    var marker;
    var content;

    // Initialize the map
    function initializeMap() {
        var myLatlng = new google.maps.LatLng(latitude, longitude);
        var mapOptions = {
            zoom: 7,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        };
        map = new google.maps.Map(document.getElementById("map"), mapOptions);
        content = document.getElementById('information');

        marker = new google.maps.Marker({
            map: map,
            position: myLatlng,
            draggable: true // Allow marker to be dragged
        });

        // Update marker position when it's dragged
        google.maps.event.addListener(marker, 'dragend', function (event) {
            placeMarker(event.latLng);
        });

        // Update marker position when map is clicked
        google.maps.event.addListener(map, 'click', function (event) {
            placeMarker(event.latLng);
        });

        var input = document.getElementById('search_input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        google.maps.event.addListener(searchBox, 'places_changed', function () {
            var places = searchBox.getPlaces();
            if (places.length == 0) {
                return;
            }
            var place = places[0];
            if (!place.geometry) {
                console.log("Place details not found for " + place.name);
                return;
            }
            // If a place is found, update marker position
            placeMarker(place.geometry.location);
        });


        // Enable users to draw shapes on the map
        var drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: google.maps.drawing.OverlayType.MARKER,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: ['marker', 'circle', 'polygon', 'polyline', 'rectangle']
            }
        });
        drawingManager.setMap(map);

        // Allow the map to automatically center on the user's current location.
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                map.setCenter(pos);
                marker.setPosition(pos); // Optionally set marker to user's location
            });
        }


    }

    // Update marker and map position
    function placeMarker(location) {
        marker.setPosition(location);
        map.panTo(location);
        content.innerHTML = "Lat: " + location.lat() + " / Long: " + location.lng();
        $("#lat").val(location.lat());
        $("#lng").val(location.lng());
    }

    $(document).ready(function() {
        console.log("Document is ready.");
    });
</script>
