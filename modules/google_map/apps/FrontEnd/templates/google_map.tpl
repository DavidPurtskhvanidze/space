<div class="map">
{if empty($GLOBALS.settings.google_maps_API_key)}
    <p class="notice">[[Location Map]]. [[Warning: The map can not be displayed. Google Maps API key is not configured. Please <a href="https://code.google.com/apis/console/">sign up for the Google Maps API</a> and then specify it in Admin Panel -> System Settings -> Google Maps API key.]]</p>
    {else}
    <div class="mapHeader"></div>
    <div class="mapWrapper">
        <div id="Map"></div>
    </div>
    <div class="mapFooter"></div>
    {require component="jquery" file="jquery.js"}
    <script type="text/javascript"
            src="http://maps.google.com/maps/api/js?key={$GLOBALS.settings.google_maps_API_key}&amp;sensor=false"></script>
    <script type="text/javascript">
	    var map;
        var latitude = {$latitude};
        var longitude = {$longitude};
        var zoom = 13;
        function initialize() {
            map = new google.maps.Map(
                    document.getElementById('Map'), {
                        center:new google.maps.LatLng(latitude, longitude),
                        panControl:true,
                        scaleControl:true,
                        overviewMapControl:true,
                        zoom:zoom,
                        mapTypeId:google.maps.MapTypeId.ROADMAP
                    });
            var marker = new google.maps.Marker({
                position:new google.maps.LatLng(latitude, longitude),
                map:map
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
{/if}
</div>
