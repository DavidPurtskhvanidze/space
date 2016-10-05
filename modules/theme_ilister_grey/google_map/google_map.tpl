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
		<script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;hl=en&amp;key={$GLOBALS.settings.google_maps_API_key}"></script>
		<script type="text/javascript">
			var latitude = {$latitude};
			var longitude = {$longitude};
			var zoom = 13;
			{literal}
			$(document).ready(function(){
				if (GBrowserIsCompatible()) {
					var mapContainer = document.getElementById("Map");
					var map = new GMap2(mapContainer);
					map.setCenter(new GLatLng(latitude, longitude), zoom);
					var point = new GLatLng(latitude, longitude);
					var marker = new GMarker(point);
						
					map.addOverlay(marker);
					GEvent.addListener(marker, "click", function() {
						 window.open($(".gmnoprint a").attr("href"), '_blank');
					});
						
					map.onContainerChanged = function() // this fixes map in hidden divs
					{
						map.checkResize();
						map.setCenter(new GLatLng(latitude, longitude), zoom);
					}
					mapContainer.map = map;
				}
			});
			$(window).unload(GUnload());
			{/literal}
		</script>
	{/if}
</div>
