{$values = ["ListingRating"=>"Listing Rating", "Price"=>"Price", "activation_date"=>"Posted"]}
{foreach $listing_search.sorting_fields as $sortedByKey=>$sortedByValue}
	{if $sortedByValue@last}
		{$sortedBy = $sortedByKey}
		{if $sortedByValue == "ASC"}
			{$order = 'ascending'}
		{else}
			{$order = 'descending'}
		{/if}
	{/if}
{/foreach}
{if isset($values.$sortedBy)}
	<a href="#" class="caption">[[Sorted by]] [[FormFieldCaptions!{$values.$sortedBy}]], [[{$order}]]</a>
{else}
	<a href="#" class="caption">[[Sort by]]</a>
{/if}
<ul class="fallback">
	{foreach $values as $value=>$caption}
		{if $listing_search->isSortable($value)}
			<li>
				<span class="fieldCaption">[[FormFieldCaptions!{$caption}]],</span>
				<span class="sortingLinks">
				{if $value == $sortedBy}
					{if $order == 'ascending'}
						<span class="selected">[[Asc]]</span> /
						<a href="{$url}&amp;sorting_fields[{$value}]=DESC">[[Desc]]</a>
					{else}
						<a href="{$url}&amp;sorting_fields[{$value}]=ASC">[[Asc]]</a> /
						<span class="selected">[[Desc]]</span>
					{/if}
				{else}
					<a href="{$url}&amp;sorting_fields[{$value}]=ASC">[[Asc]]</a> /
					<a href="{$url}&amp;sorting_fields[{$value}]=DESC">[[Desc]]</a>
				{/if}
				</span>
			</li>
		{/if}
	{/foreach}
</ul>
