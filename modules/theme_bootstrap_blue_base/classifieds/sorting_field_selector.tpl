{if !isset($values)}
	{$values = ["ListingRating"=>"Listing Rating", "Price"=>"Price", "activation_date"=>"Posted", "first_activation_date"=>"Activation Date", "views"=>"Views"]}
{/if}

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

<div class="dropdown sortingFieldSelector sortMenu">
    <span class="label">[[Sorted by]]</span> <br />
	<a id="SortingFieldSelector" data-toggle="dropdown" href="#">
		{if isset($values.$sortedBy)}
			 [[FormFieldCaptions!{$values.$sortedBy}]], [[{$order}]]
        {else}
            [[FormFieldCaptions!{$values.activation_date}]], [[ascending]]
		{/if}
		<i class="fa fa-chevron-down pull-right"></i>
	</a>
	<ul class="dropdown-menu" role="menu" aria-labelledby="SortingFieldSelector">
		{foreach $values as $value=>$caption}
			{if $listing_search->isSortable($value)}
                <li role="presentation">
                    <span class="fieldCaption">[[FormFieldCaptions!{$caption}]],</span>
                <span class="sortingLinks">
                {if $value == $sortedBy}
                    {if $order == 'ascending'}
                        <span class="selected">[[Asc]]</span> /
                        <a role="menuitem" href="{$url}&sorting_fields[{$value}]=DESC">[[Desc]]</a>
                    {else}
                        <a role="menuitem" href="{$url}&sorting_fields[{$value}]=ASC">[[Asc]]</a> /
                        <span class="selected">[[Desc]]</span>
                    {/if}
                {else}
                    <a role="menuitem" href="{$url}&sorting_fields[{$value}]=ASC">[[Asc]]</a> /
                    <a role="menuitem" href="{$url}&sorting_fields[{$value}]=DESC">[[Desc]]</a>
                {/if}
                </span>
                </li>
			{/if}
		{/foreach}

	</ul>
</div>
