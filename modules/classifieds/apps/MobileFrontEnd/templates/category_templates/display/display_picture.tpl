{assign var="uriParametersPart" value=$listing.id|cat:"/?"}
{if !empty($listing_search)}
	{assign var="uriParametersPart" value=$uriParametersPart|cat:"searchId="|cat:$listing_search.id|cat:"&amp;"}
{/if}
<div class="listingDetails">
	<div class="pictures">
		<h1>
			{$listing}
		</h1>
		{strip}
		{if $REQUEST.img_num}
			{assign var='imgNum' value=$REQUEST.img_num}
			{if $imgNum<0 || $imgNum>=$listing.pictures.numberOfItems}
				{assign var='imgNum' value=0}
			{/if}
		{else}
			{assign var='imgNum' value=0}
		{/if}
		<div class="pictureContainer">
			<div class="pictureResizer" style="width:90%; max-width:300px;">
                {listing_image pictureInfo=$listing.pictures.collection.$imgNum}
			</div>
		</div>
		{if $listing.pictures.numberOfItems>1}
			<div class="pictureSelector">
				<div class="prevPictureSelector">
					{if $imgNum>0}
						<a href="{$GLOBALS.site_url}/listing/picture/{$uriParametersPart}img_num={$imgNum-1}">« [[Previous]]</a>
					{else}
						<span>« [[Previous]]</span>
					{/if}
				</div>
				<div class="nextPictureSelector">
					{if $imgNum<$listing.pictures.numberOfItems-1}
						<a href="{$GLOBALS.site_url}/listing/picture/{$uriParametersPart}img_num={$imgNum+1}">[[Next]] »</a>
					{else}
						<span>[[Next]] »</span>
					{/if}
				</div>
				<span class="currPictureInfoAndControlsWrapper">
					<span class="pictureInfo">{$imgNum+1} [[of]] {$listing.pictures.numberOfItems}</span>
					<span class="controlsSeparator">|</span>
					<span class="pictureControls"><a href="{page_path id='listing_pictures'}{$uriParametersPart}">[[View all]]</a></span>
				</span>
			</div>
		{/if}
		{/strip}
	</div>
	{include file="classifieds^category_templates/display/subpages_links.tpl" currentPageId="pictures" listing=$listing}
	{include file="classifieds^category_templates/display/search_results_controls.tpl"}
</div>
