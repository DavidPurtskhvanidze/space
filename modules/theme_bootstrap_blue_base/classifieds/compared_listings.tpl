{require component="jquery" file="jquery.js"}
{require component="twitter-bootstrap" file="css/bootstrap.min.css"}
{require component="twitter-bootstrap" file="js/bootstrap.min.js"}

{extension_point name='modules\classifieds\apps\FrontEnd\IComparedListingsAdditionRenderer'}
<div class="compareListingsPage">
	{if !$compareListIsEmpty}
	{function name=displayComparisonTableRow}
		<tr>
			<th class="fieldCaption fieldCaption{$field.id}">
				<span>[[FormFieldCaptions!{$field.caption}]]</span>
			</th>
			{foreach from=$listings item=listing}
				<td class="fieldValue fieldValue{$field.id}">
					{if $listing.$fieldId.exists}
						{if $fieldId == 'Year' or $fieldId == 'YearBuilt'}
							<span>{$listing.$fieldId}</span>
						{elseif $field.type == 'money'}
							<span class="{$field.type}">{$GLOBALS.custom_settings.listing_currency}{tr resolveMetadataFor='listing.'|cat:$fieldId}{$listing.$fieldId}{/tr}</span>
						{else}
							<span>{tr resolveMetadataFor='listing.'|cat:$fieldId}{$listing.$fieldId}{/tr}</span>
						{/if}
					{else}
						<span class="notApplicable">[[N/A]]</span>
					{/if}
				</td>
			{/foreach}
		</tr>
	{/function}
		<h1 class="page-title">[[Compare Listings]]</h1>
        <div class="space-20"></div>
        <div class="space-20"></div>
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover table-condensed" style="width:{math equation="x*200+200" x=$listings|@count}px"> {* 200px - compared item td width, 200px - caption td width *}
				<tr class="controls">
					<td>
						<span>
							<a class="massActionControls clear" href="{page_path module='classifieds' function='clear_comparison'}">
                                <i class="fa fa-trash"></i>
                                [[Remove all]]
                            </a>
						</span>
					</td>
					{assign var="currentPageUrl" value=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri|urlencode}
					{foreach from=$listings item=listing}
						<td>
                            <span>
                                <a class="itemControls remove" href="{page_path module='classifieds' function='remove_from_comparison'}?listing_id={$listing.id}&amp;HTTP_REFERER={$currentPageUrl}">
                                    <i class="fa fa-trash"></i>
                                    [[Remove]]
                                </a>
                            </span>
						</td>
					{/foreach}
				</tr>
				<tr>
					<td></td>
					{foreach from=$listings item=listing}
						<td class="fieldValue fieldValuePictures">
							<a href="{page_path id='listing'}{$listing.id}/">
                        <span>
                            {if $listing.pictures.numberOfItems > 0}
	                            {listing_image pictureInfo=$listing.pictures.collection.0}
                            {else}
	                            <img src="{url file='main^no_image_available_small.png'}" alt="[[No photos:raw]]" class="noImageAvailable"/>
                            {/if}
                        </span>
							</a>
						</td>
					{/foreach}
				</tr>
				<tr>
					<td></td>
					{foreach from=$listings item=listing}
						<td class="fieldValue fieldValueCaption">
                            <span>
                                <a href="{page_path id='listing'}{$listing.id}/">{$listing}</a>
                            </span>
						</td>
					{/foreach}
				</tr>
				<tr>
					<td class="fieldCaption">
						<span>&nbsp;</span>
					</td>
					{foreach from=$listings item=listing}
						<td class="fieldValue fieldValueRating">
                    <span>
                        {include file="rating.tpl"}
                    </span>
						</td>
					{/foreach}
				</tr>
				{foreach from=$mergedListingfields item="field" key="fieldId"}
					{if $fieldId != 'Video'
					&& $fieldId != 'pictures'
					&& $fieldId != 'AvailabilityCalendar'
					&& $fieldId != 'feature_featured'
					&& $fieldId != 'feature_sponsored'
					&& $fieldId != 'feature_highlighted'
					&& $fieldId != 'feature_slideshow'
					&& $fieldId != 'feature_youtube'
					&& $fieldId != 'feature_youtube_video_id'
					&& $fieldId != 'activation_date'
					&& $fieldId != 'ListingRating'
					&& $fieldId != 'Title'
					&& $fieldId != 'category'
					&& $field.type != 'file'
					&& $field.type != 'text'
					}
						{displayComparisonTableRow field=$field}
					{/if}
				{/foreach}
				{foreach from=$mergedListingfields item="field" key="fieldId"}
					{if $field.type == 'text'}
						{displayComparisonTableRow field=$field}
					{/if}
				{/foreach}
			</table>

		</div>
	{else}
		<div class="info">
			{$link={page_path id='search'}}
			[[There is no listing to compare. Please <a href="$link">find more listings</a> to add them to the comparison table.]]
		</div>
		{require component="jquery" file="jquery.js"}
		<script type="text/javascript">
			$(document).ready(function () {
				$(".info a").click(function () {
					window.opener.location.href = $(this).attr("href");
					window.close();
				});
			});
		</script>
	{/if}
</div>

