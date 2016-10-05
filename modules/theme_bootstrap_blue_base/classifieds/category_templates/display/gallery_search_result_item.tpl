{capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html?searchId={$listing_search.id}{/capture}
<div class="thumbnail">
    <div class="search-result-item-header">
        <div class="row">
            <div class="col-xs-6 text-left">
                <span class="fieldValue fieldValueListingRating">{include file="rating.tpl"}</span>
            </div>
            <div class="col-xs-6 text-right">
                {include file=$listingControlsTemplate listingUrl=$listingUrl}
            </div>
        </div>
    </div>
	<div class="image">
		{module name="classifieds" function="display_quick_view_button" listing=$listing}
        <span class="mask"></span>
		{assign var="number_of_pictures" value=$listing.pictures.numberOfItems}
		<a href="{$listingUrl}">
			{if $number_of_pictures > 0}
				{listing_image pictureInfo=$listing.pictures.collection.0 alt="Listing #"|cat:$listing.id}
			{else}
				<img src="{url file='main^no_image_available_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable"/>
			{/if}


		</a>
	</div>
    <div class="caption">
        <h3>
            <a href="{$listingUrl}" title='{$listing|strip_tags}'>{$listing|cat:""|strip_tags:false}</a>
            <span class="paragraph-end"></span>
        </h3>
        <div class="row">
            <div class="col-xs-6">
                <p class="h4 orange">{include file="miscellaneous^listing/price.tpl"}</p>
            </div>
            <div class="col-xs-6 text-right">
                {if $listing.Sold.exists && $listing.Sold.isTrue}
                    <div class="sold-label"><span>[[SOLD]]</span></div>
                {else}
                    {module name="listing_feature_sponsored" function="display_label" listing=$listing}
                {/if}
            </div>
        </div>

    </div>
</div>
