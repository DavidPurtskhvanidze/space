<div class="sortingFieldSelector">
	{strip}
	<span class="selectorLabel">[[Sort by:]]</span>
	<ul class="sortingFields">
		{if $listing_search->isSortable('activation_date')}
			<li>
				<a href="?action=restore&amp;searchId={$listing_search.id}&amp;sorting_fields[activation_date]={if $listing_search.sorting_fields['activation_date'] == 'DESC'}ASC{else}DESC{/if}">[[FormFieldCaptions!Posted]]</a>
				{if $listing_search.sorting_fields['activation_date']}
					{if $listing_search.sorting_fields['activation_date'] == 'ASC'}&uarr;{else}&darr;{/if}
				{/if}
			</li>
	    {/if}
		{if $listing_search->isSortable('Price')}
			<li>
				<a href="?action=restore&amp;searchId={$listing_search.id}&amp;sorting_fields[Price]={if $listing_search.sorting_fields['Price'] == 'DESC'}ASC{else}DESC{/if}">[[FormFieldCaptions!Price]]</a>
				{if $listing_search.sorting_fields['Price']}
					{if $listing_search.sorting_fields['Price'] == 'ASC'}&uarr;{else}&darr;{/if}
				{/if}
			</li>
		{/if}
	<ul>
	{/strip}
</div>
