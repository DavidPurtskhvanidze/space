{function name=displayFields}
	{foreach $fields as $sortedByKey=>$sortedByCaption}
		<li>
			<span class="fieldCaption">
				[[FormFieldCaptions!{$sortedByCaption}]],
			</span>
			{if $sortedByKey == $currentSortedBy}
				{if $currentOrder == 'ascending'}
					<span class="current">[[Asc]]</span>
					/
					<a class="change-order" href="{$url}&amp;sorting_fields[{$sortedByKey}]=DESC">[[Desc]]</a>
				{else}
					<a class="change-order" href="{$url}&amp;sorting_fields[{$sortedByKey}]=ASC">[[Asc]]</a>
					/
					<span class="current">[[Desc]]</span>
				{/if}
			{else}
				<a class="change-order" href="{$url}&amp;sorting_fields[{$sortedByKey}]=ASC">[[Asc]]</a>
				/
				<a class="change-order" href="{$url}&amp;sorting_fields[{$sortedByKey}]=DESC">[[Desc]]</a>
			{/if}
		</li>
	{/foreach}
{/function}

{if !$moreSortingFields}
    {$moreSortingFields = array()}
{/if}

{$allSortingFields = array_merge($sortingFields, $moreSortingFields)}
{foreach $allSortingFields as $sortedByKey=>$sortedByCaption}
    {if $search.sorting_fields[$sortedByKey]}
        {$currentSortedBy = $sortedByKey}
        {if $search.sorting_fields[$sortedByKey] == "ASC"}
            {$currentOrder = 'ascending'}
        {else}
            {$currentOrder = 'descending'}
        {/if}
    {/if}
{/foreach}

<div class="btn-group sort-by">
	<button class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown">
		{if isset($currentSortedBy)}
			[[Sorted by]] [[FormFieldCaptions!{$allSortingFields[$currentSortedBy]}]], [[{$currentOrder}]]
		{else}
			[[Sort by]]
		{/if}
		<i class="icon-angle-down icon-on-right"></i>
	</button>

	<ul class="dropdown-menu dropdown-info pull-right">
		{displayFields fields=$sortingFields}
		{if $moreSortingFields}
			<li class="divider"></li>
			<li class="dropdown-hover">
				<a href="#" class="clearfix">
					<span class="pull-left">[[More sorting options]]</span>
				</a>
				<ul class="dropdown-menu dropdown-info">
					{displayFields fields=$moreSortingFields}
				</ul>
			</li>
		{/if}
	</ul>
</div>
