<div class="map">
	{if !$REQUEST.zoom || $REQUEST.zoom<1 || $REQUEST.zoom>21}
		{assign var='mapZoomRate' value='15'}
	{else}
		{assign var='mapZoomRate' value=$REQUEST.zoom}
	{/if}
	<img src="http://maps.google.com/maps/api/staticmap?size=300x300&markers={$latitude},{$longitude}&sensor=false&zoom={$mapZoomRate}" />
	<div class="mapControls">
		{assign var='searchId' value=''}
		{if !empty($listing_search)}
			{assign var='searchId' value='&searchId='|cat:$listing_search.id}
		{/if}

		{if $mapZoomRate>1}
			<a href='?zoom={$mapZoomRate-1}'>[[Zoom out]]</a>
		{else}
			<span>[[Zoom out]]<span>
		{/if}
		|
		{if $mapZoomRate<21}
			<a href='?zoom={$mapZoomRate+1}'>[[Zoom in]]</a>
		{else}
			<span>[[Zoom in]]<span>
		{/if}
	</div>
</div>
