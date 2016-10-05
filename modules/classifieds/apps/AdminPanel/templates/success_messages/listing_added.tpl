{assign var="listingUrl" value={page_path id='display_listing'}|cat:"?listing_id="|cat:$listingId}
<div class="success">[[Listing <a href="$listingUrl">#$listingId</a> was successfully added to the system!]]</div>
