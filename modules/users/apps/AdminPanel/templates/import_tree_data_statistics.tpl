<div class="ipBlockList">
	<div class="breadcrumbs">
	<a href="{page_path id='user_groups'}">[[User Groups]]</a>
	{if $type_info.sid != 0}
	&gt; <a href="{page_path id='edit_user_group'}?sid={$type_sid}">{$type_info.id}</a>
	{/if}
	&gt; <a href="{page_path id='edit_user_profile'}?user_group_sid={$type_info.sid}">[[Edit User Profile Fields]]</a>
	&gt; <a href="{page_path id='edit_user_profile_field'}?sid={$field_sid}&amp;user_group_sid={$type_info.sid}">[[$field.caption]]</a>
	</div>

	<h1>[[Import Tree Data]]</h1>

	[[Number of imported items:]] {$count}
</div>
