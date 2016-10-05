<div class="addComment">
    <div class="addCommentHeader h5">
        {if $commentSid}[[Reply to The Comment]]{else}[[Add Your Comment]]{/if}
    </div>
	{display_error_messages}
	<form action="{page_path id='comment_add'}" method="post">
        {CSRF_token}
		<div class="addCommentForm">
			<input type="hidden" name="action" value="add_comment"/>
			<input type="hidden" name="listingSid" value="{$listingSid}"/>
			<input type="hidden" name="commentSid" value="{$commentSid}"/>
			<input type="hidden" name="returnBackUri" value="{$returnBackUri}"/>
			{$parameters['height']="150px"}
			<div>{input property="comment" parameters=$parameters}</div>
			{if !$commentSid}
				<div>[[How do you rate this listing?]]</div>
				<div>{input property="ListingRating" template="integer_rating.tpl"}</div>
			{/if}
			<div class="space-20"></div>
			<div>
                <input type="submit" value="{if $commentSid}[[Add your reply:raw]]{else}[[Add your comment:raw]]{/if}" class="btn btn-orange h5"/>
                <a class='cancelAddComment btn btn-white large' href="#" title="[[Cancel]]">[[Cancel]]</a>
            </div>
		</div>
	</form>
</div>
<script type="text/javascript" src="{url file="field_types^showInputError.js"}"></script>
