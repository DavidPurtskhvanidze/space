<ul class="searchControls">
	{if $listing_search.prev}
		<li class="prevPageSelector {if !$listing_search.next}singlePrevPageSelector{/if}">
			<a class="prevPageButton" href="{page_path id='listing'}{$listing_search.prev}/?searchId={$listing_search.id}">&laquo;</a>
			<a class="prevPageCaption" href="{page_path id='listing'}{$listing_search.prev}/?searchId={$listing_search.id}">[[Previous]]</a>
		</li>
	{/if}
	{if $listing_search.next}
		<li class="nextPageSelector">
			<a class="nextPageCaption" href="{page_path id='listing'}{$listing_search.next}/?searchId={$listing_search.id}">[[Next]]</a>
			<a class="nextPageButton" href="{page_path id='listing'}{$listing_search.next}/?searchId={$listing_search.id}">&raquo;</a>
		</li>
	{/if}
</ul>
