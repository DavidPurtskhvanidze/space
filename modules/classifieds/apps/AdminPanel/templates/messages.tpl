{assign var="destinationCategorySid" value=$GLOBALS.site_url|cat:"/edit_category/?sid="|cat:$destination_category.sid}
{foreach from=$messages item=message key=key}
	<p class="success">
		{if $message == 'msg_cat_moved'}
			[[Category was successfully relocated within current level.]]
		{elseif $message == 'msg_cat_moved_in'}
			[[Category successfully relocated to <a href="$destinationCategorySid">$destination_category.id</a>.]]
		{elseif $message == 'msg_cat_moved_down'}
			[[Category successfully moved down.]]
		{elseif $message == 'msg_cat_moved_up'}
			[[Category successfully moved up.]]
		{elseif $message == 'msg_catfield_moved_down'}
			[[Field successfully moved down.]]
		{elseif $message == 'msg_catfield_moved_up'}
			[[Field successfully moved up.]]
		{/if}
	</p>
{/foreach}
