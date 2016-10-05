<div class="editCommentForm">
{if $actionDone}
	<script type="text/javascript">
		this.location.href = "{page_path module='listing_comments' function='manage_comments'}?action=restore&searchId={$searchId}";
	</script>
{else}
	<h4>[[Edit comment]]</h4>
	{display_error_messages}

	<form action="" class="form form-horizontal">
		<input type="hidden" name="action" value="save_comment">
		<input type="hidden" name="commentSid" value="{$commentSid}">
		<input type="hidden" name="searchId" value="{$searchId}">
		<div class="form-group">
			<label class="control-label col-sm-3">[[Comment]]</label>
			<div class="col-sm-9">{input property="comment"}</div>
		</div>
		<div class="clearfix form-actions"><input type="submit" value="[[Save:raw]]" class="btn btn-default"></div>
	</form>
{/if}
</div>
