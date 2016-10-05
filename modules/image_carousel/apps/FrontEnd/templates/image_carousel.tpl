{require component="jquery" file="jquery.js"}
{require component="rhinoslider" file="rhinoslider-1.04.js"}

<div class="carousel {if $showNumbers}withNumbers{/if}">
	<ul id="slider" style="height: {$height}px; width: {$width}px">
		{foreach from=$images item=image}
			<li>
				{if $image.url}<a href="{if strpos($image.url, '/') === 0}{$GLOBALS.site_url}{/if}{$image.url}">{/if}
					<img src="{$image.image.original.url}" title="{$image.caption}" />
				{if $image.url}</a>{/if}
			</li>
		{/foreach}
	</ul>
</div>

<script type="text/javascript">
	var transitionTime = {$transitionTime};
	var showArrows = '{$showArrows}' == '1';
	$(document).ready(function () {
		$('#slider').rhinoslider({
			effect:'fade',
			showTime: transitionTime * 1000,
			effectTime:1000,
			easing:'easeInQuad',
			controlsMousewheel:false,
			controlsKeyboard:false,
			controlsPlayPause:false,
			autoPlay:true,
			controlFadeTime:500,
			showBullets:'always',
			changeBullets: 'before',
			controlsPrevNext: showArrows
		});
	});
</script>
