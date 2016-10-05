{$listingsPerPageValueList = [10,20,50,100]}

<a id="ObjectsPerPageSelector" data-toggle="dropdown" href="#">
	{$listing_search.objects_per_page} [[listings per page]] <span class="caret"></span>
</a>
<ul class="dropdown-menu" role="menu" aria-labelledby="SortingFieldSelector">
	{foreach $listingsPerPageValueList as $listingPerPage}
		<li role="presentation" {if $listing_search.objects_per_page eq $listingPerPage} class="active" {/if}>
			<a role="menuitem" href="{$url}&amp;listings_per_page={$listingPerPage}&amp;page=1">{$listingPerPage}</a>
		</li>
	{/foreach}
</ul>
