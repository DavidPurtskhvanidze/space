<div class="addComment">
    <h3 class="addCommentHeader">
        {if $commentSid}[[Reply to The Comment]]{else}[[Add Your Comment]]{/if}
    </h3>
	{display_error_messages}
	<form action="{page_path id='comment_add'}" method="post">
        {CSRF_token}
		<div class="addCommentForm">
			<input type="hidden" name="action" value="add_comment"/>
			<input type="hidden" name="listingSid" value="{$listingSid}"/>
			<input type="hidden" name="commentSid" value="{$commentSid}"/>
			<input type="hidden" name="returnBackUri" value="{$returnBackUri}"/>
			<br/>
			{$parameters['height']="150px"}
			<div>{input property="comment" parameters=$parameters}</div>
			<br/>
			{if !$commentSid}
				<div>[[How do you rate this listing?]]</div>
				<div>{input property="ListingRating" template="integer_rating.tpl"}</div>
			{/if}
			<div class="space-20"></div>
			<div>
                <input type="submit" value="{if $commentSid}[[Add your reply:raw]]{else}[[Add your comment:raw]]{/if}" class="btn btn-default"/>
                <a class='cancelAddComment btn btn-default' href="#" title="[[Cancel]]">[[Cancel]]</a>
            </div>
		</div>
	</form>
</div>
<script type="text/javascript" src="{url file="field_types^showInputError.js"}"></script>
