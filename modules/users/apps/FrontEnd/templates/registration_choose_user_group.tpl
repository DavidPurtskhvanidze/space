<h1>[[Registration]]</h1>
<br />
<p>[[Please select the appropriate user group]]:</p>
<br />
<ul class="userGroupInfo">
	{foreach from=$user_groups_info item=user_group_info}
		<li class="userGroupInfo">
			<a href="?user_group_id={$user_group_info.id}">[[PhrasesInTemplates!{$user_group_info.name}]]</a>
			<div>[[PhrasesInTemplates!{$user_group_info.description}]]</div>
			<br />
		</li>
	{/foreach}
</ul>
