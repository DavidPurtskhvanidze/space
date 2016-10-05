{if !empty($listing_search)}
	<ul class="list-inline">
		{if $listing_search.prev}
			<li>
				<a href="{page_path id='listing'}{$listing_search.prev}/?searchId={$listing_search.id}">[[Previous]]</a>
			</li>
		{/if}
		{if $listing_search.next}
			<li>
				<a href="{page_path id='listing'}{$listing_search.next}/?searchId={$listing_search.id}">[[Next]]</a>
			</li>
		{/if}
	</ul>
{/if}
