<a href="#" class="caption">{$listing_search.objects_per_page} [[listings per page]]</a>
{$listingsPerPageValueList = [10,20,50,100]}
<ul>
{foreach $listingsPerPageValueList as $listingPerPage}
	<li>
		<a href="{$url}&amp;listings_per_page={$listingPerPage}&amp;page=1">{$listingPerPage}</a>
	</li>
{/foreach}
</ul>
