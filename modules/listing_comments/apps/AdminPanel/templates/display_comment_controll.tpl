{if $controll == 'ADD'}
	{include file="miscellaneous^dialog_window.tpl"}
    <a onclick="return openDialogWindow('[[Add a comment:raw]]', this.href, 500, true)"  href="{page_path module='listing_comments' function='add_listing_comment'}?searchId={$listing_search.id}&amp;listingSid={$listing.id}&amp;returnBackUri={$returnBackUri|urlencode}" title="[[Add a comment]]">
        {*<i class="icon-comments"></i>*}
        [[Add a comment]]
    </a>
	
{elseif $controll == 'MANAGE'}
	{if $listing.numberOfComments.value > 0}
		<a href="{page_path module='listing_comments' function='manage_comments'}?action=search&listing_id[equal]={$listing.sid}" title="[[Manage comments]]{if $REQUEST.includeCommentCount} ({$listing.numberOfComments}){/if}">
      [[Manage comments]]
    </a>
	{/if}
{elseif $controll == 'ADD_OR_MANAGE'}
	{if $listing.numberOfComments.value > 0}
		<a class="btn btn-xs btn-success" href="{page_path module='listing_comments' function='manage_comments'}?action=search&listing_id[equal]={$listing.sid}" title="[[Manage comments]]{if $REQUEST.includeCommentCount} ({$listing.numberOfComments}){/if}">
			<i class="icon-comment"></i>
		</a>
	{else}
		{include file="miscellaneous^dialog_window.tpl"}
        <a onclick="return openDialogWindow('[[Add a comment:raw]]', this.href, 500, true)" class="btn btn-xs btn-comments" href="{page_path module='listing_comments' function='add_listing_comment'}?searchId={$listing_search.id}&amp;listingSid={$listing.id}&amp;returnBackUri={$returnBackUri|urlencode}" title="[[Add a comment]]">
            <i class="icon-comments"></i>
        </a>
	{/if}
{elseif $controll == 'NUMBER_OF_COMMNETS'}
	{$listing.numberOfComments} [[comments]]
{/if}
