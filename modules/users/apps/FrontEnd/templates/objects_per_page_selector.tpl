<a href="#" class="caption">{$search.objects_per_page} [[records per page]]</a>
{$recordPerPageValueList = [10,20,50,100]}
<ul>
{foreach $recordPerPageValueList as $recordPerPage}
	<li>
		<a href="{$url}&amp;items_per_page={$recordPerPage}&amp;page=1">{$recordPerPage}</a>
	</li>
{/foreach}
</ul>
