<div class="listingAllImagesPage">
	<h1>{if $listing.Sold.exists && $listing.Sold.isTrue}<span class="fieldValue fieldValueSold">[[SOLD]]!</span> {/if}{$listing} {if $listing.Price.exists}<span class="fieldValue fieldValuePrice">{$GLOBALS.custom_settings.listing_currency}[[$listing.Price]]</span>{/if}</h1>
	<div class="allPictures">
	{if $listing.pictures.numberOfItems > 0}
		{assign var="mainPicture" value=$listing.pictures.collection.0}
		<div class="imageFrame">
			{listing_image pictureInfo=$mainPicture}
		</div>
		<ul class="imageThumbs">
			{foreach from=$listing.pictures.collection key=key item=picture name=thumbnails}
				<li>
					<a href="{$picture.file.picture.url}" class="thumbnail{if $smarty.foreach.thumbnails.first} selected{/if}">
						{listing_image pictureInfo=$picture thumbnail=1}
					</a>
				</li>
			{/foreach}
		</ul>
		{require component="jquery" file="jquery.js"}
		{literal}
		<script type="text/javascript">
		$(document).ready(function(){
			$('.imageThumbs .thumbnail').click(function(){
				$('.imageFrame img').attr('src', $(this).attr('href'));
				$('.imageFrame img').attr('alt', $(this).attr('title'));
				$('.imageThumbs .thumbnail').removeClass('selected');
				$(this).addClass('selected');
				return false;
			});
		});
		</script>
		{/literal}
	{/if}
	</div>
	<a href="{page_path id='listing'}{$listing.id}/{if isset($listing_search)}?searchId={$listing_search.id}{/if}">[[Back to the Listing]]</a>
</div>
