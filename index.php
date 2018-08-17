<!doctype html>
<html>
  <head>
    <title>Google Maps Example</title>
    <script src="https://cdn.pubnub.com/sdk/javascript/pubnub.4.19.0.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
  </head>
  <body>
    <div class="container">
       <div id="map-canvas" style="width:600px;height:600px"></div>
    </div>

    <script>
    window.lat = 22.572645;
    window.lng = 88.363892;

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(updatePosition);
        }
      
        return null;
    };

    function updatePosition(position) {
      if (position) {
        window.lat = position.coords.latitude;
        window.lng = position.coords.longitude;
      }
    }
    
    setInterval(function(){updatePosition(getLocation());}, 1000);
      
    function currentLocation() {
      return {lat:window.lat, lng:window.lng};
    };

    var map;
    var mark;

    var initialize = function() {
      map  = new google.maps.Map(document.getElementById('map-canvas'), {center:{lat:lat,lng:lng},zoom:18});
      mark = new google.maps.Marker({position:{lat:lat, lng:lng}, map:map});
    };

    window.initialize = initialize;

    var redraw = function(payload) {
      lat = payload.message.lat;
      lng = payload.message.lng;

      map.setCenter({lat:lat, lng:lng, alt:0});
      mark.setPosition({lat:lat, lng:lng, alt:0});
    };

    var pnChannel = "map2-channel";

    var pubnub = new PubNub({
      publishKey:   'pub-c-9c41a3db-1988-4659-825d-fbf8d7840c32',
      subscribeKey: 'sub-c-c1e2bc80-95a8-11e8-ae35-628057e6ebd4'
    });

    pubnub.subscribe({channels: [pnChannel]});
    pubnub.addListener({message:redraw});

    setInterval(function() {
      pubnub.publish({channel:pnChannel, message:currentLocation()});
    }, 1000);
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&callback=initialize"></script>
  </body>
</html>