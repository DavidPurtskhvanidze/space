{$recordPerPageValueList = [10,20,50,100]}

<a id="ObjectsPerPageSelector" data-toggle="dropdown" href="#">
	{$search.objects_per_page} [[records per page]] <span class="caret"></span>
</a>
<ul class="dropdown-menu" role="menu" aria-labelledby="SortingFieldSelector">
	{foreach $recordPerPageValueList as $recordPerPage}
		<li role="presentation">
			<a role="menuitem" href="{$url}&amp;items_per_page={$recordPerPage}&amp;page=1">{$recordPerPage}</a>
		</li>
	{/foreach}
</ul>
