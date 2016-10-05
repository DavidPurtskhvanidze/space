{require component="js" file="toggleListingAction.js"}
<div class="listingControls" data-listingId="{$listing.id}">
	<ul>
	    <li><a href="{$listingUrl}">[[View Details]]</a></li>
	    <li>|</li>
	    <li><a href="?action=delete&amp;listings[{$listing.id}]=1">[[Delete]]</a></li>
		<li>|</li>
		<li>
			<label><input type="checkbox" name="compareAddSwitch" onclick="toggleListingAction(this, '{page_path id='listing_compare'}', '{page_path module='classifieds' function='remove_from_comparison'}')" value="{$listing.id}"{if $listing.inComparison.isTrue} checked="checked"{/if} /><span class="label">[[Compare Ad]]</span></label>
		</li>
		<li class="compareListingsDelim">|</li>
		<li class="compareListingsLink"><a target="_blank" href="{page_path id='compared_listings'}">[[Compare Selected Listings]]</a></li>
	</ul>
</div>
