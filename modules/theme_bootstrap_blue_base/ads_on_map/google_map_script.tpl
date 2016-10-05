{require component="jquery" file="jquery.js"}
{require component="absolute_url" type="js" file="https://maps.googleapis.com/maps/api/js?sensor=false"}
{require component="google-maps-utility-library" file="markerclusterer.js"}
<script type="text/javascript">
    var map;
    var newyork = new google.maps.LatLng(40.69847032728747, -73.9514422416687);
    var circle=new google.maps.Circle({ visible:false});
    var markerCluster;
    var clusterClicked = false;

    function getUserLocation() {
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({ 'address':default_location}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
            }
            else {
                map.setCenter(newyork);
            }
        });

        if(navigator.geolocation)
        {
            navigator.geolocation.getCurrentPosition(function(position){
                map.setCenter(new google.maps.LatLng(position.coords.latitude,position.coords.longitude));})
        }

    }
    function getMapOptions()
    {
        return{
            'zoom':4,
            'mapTypeId':google.maps.MapTypeId.ROADMAP,
            'overviewMapControl':true,
            'mapTypeControlOptions':{ position: google.maps.ControlPosition.RIGHT_BOTTOM },
            'scaleControl':true,
            'overviewMapControlOptions':{ opened:true,'zoom':4}};
    }

    function getAreaRadius()
    {
        var bounds = map.getBounds();
        var c=bounds.getNorthEast();
        var l=bounds.getSouthWest();
        var distance = Math.sqrt(Math.pow(69.1*(c.lat()-l.lat()),2)+Math.pow(69.1*(c.lng()-l.lng()),2));
        return distance*1.6*1000*0.15;
    }

    function drowArea(position, radius)
    {
	    if (clusterClicked)
		{
			clusterClicked = false;
			return false;
		}

        var populationOptions = {
            strokeOpacity:0,
            fillColor:'#0000DD',
            fillOpacity:0.35,
            map:map,
            center:position,
            radius:radius,
            editable:true};
        if(circle.getVisible())
        {
            circle.setVisible(false);
            $('.location_map_radius').val("");
            $('.location_map_longitude').val("");
            $('.location_map_latitude').val("");
        }
        else
        {
            circle = new google.maps.Circle(populationOptions);
            $('.location_map_longitude').val(circle.center.lng());
            $('.location_map_latitude').val(circle.center.lat());
            $('.location_map_radius').val(circle.radius/1000);
            google.maps.event.addListener(circle,'center_changed',function(){
                $('.location_map_longitude').val(circle.center.lng());
                $('.location_map_latitude').val(circle.center.lat());});

            google.maps.event.addListener(circle,'radius_changed',function(){
                $('.location_map_radius').val(circle.radius/1000);});
        }
    }

    function addMarker(listing_location) {
        var title = '';
        var content = '<div class="searchResults">';
        var listingsLength = listing_location.listings.length;
        if (listingsLength > 0)
        {
            title = listingsLength + ' ' + "[[NumberOfGroupedListings:raw]]";
        }
        for (var i = 0; i < listingsLength; i++)
        {
            if (listingsLength == 1)
                title = "# " + listing_location.listings[i] + ". " + listing_location.titles[i];
            content += $(".listingPreview." + listing_location.listings[i]).html();
        }
		content += '</div>';

        var point = new google.maps.LatLng(listing_location.latitude, listing_location.longitude);
        var marker = new google.maps.Marker({
            'position':point,
            'map':map,
            'title':title});
        google.maps.event.addListener(marker, 'click', function () {
            openContentInDialog(title, content, 960);
        });
	    markerCluster.addMarker(marker);
    }

    function restoreRequest()
    {
        if($('.location_map_radius').val()!="")
        {
            drowArea(new google.maps.LatLng($('.location_map_latitude').val(),$('.location_map_longitude').val()),$('.location_map_radius').val()*1000);
            map.setZoom(25-Math.ceil(Math.log(circle.radius)/Math.log(2)));
            map.setCenter(circle.getCenter());
        }
        else
        {
            var locationsAmount = Object.size(listing_locations);
            if(locationsAmount == 0)
            {
                getUserLocation();
            }
            else if(locationsAmount==1)
            {
                map.setZoom(13);
                for (key in listing_locations) {
                    map.setCenter(new google.maps.LatLng(listing_locations[key].latitude, listing_locations[key].longitude));
                }
            }
            else if(locationsAmount>1)
            {
                var max_longitude=-100;
                var max_latitude=-100;
                var min_longitude=100;
                var min_latitude=100;
                for (var i in listing_locations)
                {
                    if(listing_locations[i].latitude>max_latitude)max_latitude=listing_locations[i].latitude;
                    if(listing_locations[i].latitude<min_latitude)min_latitude=listing_locations[i].latitude;
                    if(listing_locations[i].longitude>max_longitude)max_longitude=listing_locations[i].longitude;
                    if(listing_locations[i].longitude<min_longitude)min_longitude=listing_locations[i].longitude;
                }
                var latitude = (max_latitude+min_latitude)/2;
                var longitude = (max_longitude+min_longitude)/2;
                var width = $(".map").width();
                var height = $(".map").height();
                var dlat = Math.abs(min_latitude - max_latitude)*1.1;
                var dlon = Math.abs(max_longitude - min_longitude)*1.1;
                var clat = Math.PI * Math.abs(min_latitude + max_latitude) / 360.;
                var C = 0.0000107288;
                var z0 = Math.ceil(Math.log(dlat / (C * height)) / Math.LN2);
                var z1 = Math.ceil(Math.log(dlon / (C * width)) / Math.LN2);
                var zoom = 18 - ((z1 > z0) ? z1 : z0)-1;
                if (zoom>13) zoom=13;
                map.setZoom(zoom);
                map.setCenter(new google.maps.LatLng(latitude,longitude));
            }
        }
        for (var key in listing_locations) {
            addMarker(listing_locations[key]);
        }
	    google.maps.event.addListener(markerCluster, 'clusterclick', function(cluster) {
			clusterClicked = true;
	    });

    }

    function initialize()
    {
        map = new google.maps.Map(document.getElementById('Map'),getMapOptions());
		markerCluster = new MarkerClusterer(map);
        google.maps.event.addListener(map, 'click', function(e) {
            drowArea(e.latLng, getAreaRadius());
        });

        if(typeof(listing_locations)=="undefined")
        {
            getUserLocation();
        }
        else
        {
            restoreRequest();
        }

	    var homeControlDiv = document.querySelector(".searchOnMap .searchForm");
	    homeControlDiv.style.margin = '10px';
	    homeControlDiv.index = 1;
	    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(homeControlDiv);
    }
    google.maps.event.addDomListener(window, 'load', initialize);
    Object.size = function(obj) {
        var size = 0, key;
        for (key in obj) {
            if (obj.hasOwnProperty(key)) size++;
        }
        return size;
    };
</script>
