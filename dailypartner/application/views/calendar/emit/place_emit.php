<style>

    .gm-style-iw {
        width: 350px !important;
        top: 15px !important;
        left: 0 !important;
        background-color: #fff;
        box-shadow: 0 1px 6px rgba(178, 178, 178, 0.6);
        border: 1px solid rgba(72, 181, 233, 0.6);
        border-radius: 2px 2px 10px 10px;
        apply 10px to the bottom corners of the infowindow
    }
</style>
<script>
    var emitmap;
    window.onload = init;
    setTimeout(function () {
        google.maps.event.trigger(emitmap, 'resize');
    }, 100);

//        emitmap.setCenter(loc);



    function init() {
        var loc = {lat: 0, lng: 0};

//        if (navigator.geolocation) {
//
//            navigator.geolocation.getCurrentPosition(function (position) {
//                loc = {
//                    lat: position.coords.latitude,
//                    lng: position.coords.longitude
//                };
//                loc = new google.maps.LatLng(Number(loc.lat), Number(loc.lng));
//
//                var marker = new google.maps.Marker({
//                    position: loc,
//                    map: emitmap,
//                    icon: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png'
//                });
//                console.log(posi);
//            }, function () {
//                handleLocationError(true, infoWindow, emitmap.getCenter());
//            });
//        } else {
//            alert("Geolocations is not supported by this browser");
//        }

//        var latLong = new google.maps.LatLng(loc.lat, loc.lng);
        var myoptions = {
            disableDefaultUI: true,
            useCurrentLocations: true,
            center: {lat : 35.8963134,lng:128.6198624},
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var input = document.getElementById('pla-input');

        emitmap = new google.maps.Map(document.getElementById('maps'), myoptions);
        emitmap.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
//        timeset();

//        var autocomplete = new google.maps.places.Autocomplete(input);
//        autocomplete.bindTo('bounds', maps);

//        var infowindow = new google.maps.InfoWindow();
//        var marker = new google.maps.Marker({
//            map: emitmap
//        });


//        autocomplete.addListener('place_changed', function () {
//            infowindow.close();
//            marker.serVisible(false);
//            var place = autocomplete.getPlace();
//
//            if (place.geometry.viewport) {
//                emitmap.fitBounds(place.geometry.viewport);
//            } else {
//                emitmap.setCenter(place.geometry.location);
//            }
//            marker.setPosition(place.geometry.location);
//            marker.setVisible(true);
//
//            var address = '';
//            if (place.address_components) {
//                address = [
//                    (place.address_components[0] && place.address_components[0].short_name || ''),
//                    (place.address_components[1] && place.address_components[1].short_name || ''),
//                    (place.address_components[2] && place.address_components[2].short_name || '')
//                ].join(' ');
//            }
//
//            infowindow.setContent('<div><string>' + place.name + '</string><br>' + address);
//            infowindow.open(emitmap.marker);
//        });

//        function setPosition(position) {
//            loc.lat = position.coords.latitude;
//            loc.lng = position.coords.longitude;
//            var geocoder = new google.maps.Geocoder();
//            var latLng = new google.maps.LatLng(loc.lat, loc.lng);
//            if (geocoder) {
//                geocoder.geocode({'latLng': latLng}, function (results, status) {
//                    if (status == google.maps.GeocoderStatus.OK) {
//                        var markerOptions = new google.maps.Marker({
//                            map: emitmap,
//                            positions: results[0].geometry.location,
//                            title: "Your location",
//                            visible: true,
//                            icon: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png'
//                        });
//                        var marker = new google.maps.Marker(markerOptions);
//                        emitmap.setCenter(results[0].geometry.location);
//                    }
//                    else {
//                        console.log("Geocoding failed: " + status);
//                    }
//                });
//            }
//
//        }

    }


</script>
<div class="modal fade" id="setPlace" data-backdrop="static" style="z-index: 1043">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a class="close" data-dismiss="modal">
                    &times;
                </a>
                <h4 class="modal-title">select place</h4>
            </div>
            <div class="modal-body">
                <input type="text" id="pla-input">
                <div id="maps"></div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-primary">selected</a>
                <a href="#" data-dismiss="modal" class="btn">Close</a>
            </div>
        </div>
    </div>
</div>
