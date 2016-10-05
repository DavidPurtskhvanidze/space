{assign var="uriParametersPart" value=$listing.id|cat:"/?"}
{if !empty($listing_search)}
	{assign var="uriParametersPart" value=$uriParametersPart|cat:"searchId="|cat:$listing_search.id|cat:"&amp;"}
{/if}
<div class="listingDetails">
	<h1>
		{$listing}
	</h1>
	<div class="pictures">
		<div class="picturesContainer">
			{foreach from=$listing.pictures.collection key=imgNum item=picture}
				<a href="{$GLOBALS.site_url}/listing/picture/{$uriParametersPart}img_num={$imgNum}">{listing_image pictureInfo=$picture thumbnail=1}</a>
			{/foreach}
		</div>
	</div>
	{include file="classifieds^category_templates/display/subpages_links.tpl" currentPageId="pictures" listing=$listing}
	{include file="classifieds^category_templates/display/search_results_controls.tpl"}
</div>
