{require component="js" file="toggleListingAction.js"}
<div class="listingControls">
	<ul>
	    <li>
	    	<label><input type="checkbox" name="saveAddSwitch" onclick="toggleListingAction(this, '{page_path id='listing_save'}', '{page_path module='classifieds' function='delete_saved_listing'}')" value="{$listing.id}"{if $listing.saved.isTrue} checked="checked"{/if} /><span class="label">[[Save Ad]]</span></label>
	    </li>
	    <li>|</li>
	    <li>
	        <label><input type="checkbox" name="compareAddSwitch" onclick="toggleListingAction(this, '{page_path id='listing_compare'}', '{page_path module='classifieds' function='remove_from_comparison'}')" value="{$listing.id}"{if $listing.inComparison.isTrue} checked="checked"{/if} /><span class="label">[[Compare Ad]]</span></label>
	    </li>
	</ul>
</div>
