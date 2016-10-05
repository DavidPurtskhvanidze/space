<div class="importListValues">
	<div class="breadcrumbs">
		<a href="{page_path id='user_groups'}">[[User Groups]]</a>
		{if $userGroup.sid}&gt; <a href="{page_path id='edit_user_group'}?sid={$userGroup.sid}">{$userGroup.name}</a>{/if}
		&gt; <a href="{page_path id='edit_user_profile'}?user_group_sid={$userGroup.sid}">[[Edit User Profile Fields]]</a>
		&gt; <a href="{page_path id='edit_user_profile_field'}?sid={$field.sid}&user_group_sid={$userGroup.sid}">{$field.caption}</a>
		&gt; <a href="{page_path id='edit_user_profile_field_edit_list'}?field_sid={$field.sid}">[[Edit List]]</a> &gt;
		[[Import List Data]]
	</div>

	<h1>[[Import List Data]]</h1>

	[[Number of imported items:]] {$count}
</div>
