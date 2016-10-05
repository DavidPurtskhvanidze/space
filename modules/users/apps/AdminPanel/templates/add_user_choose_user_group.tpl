<div class="breadcrumbs">
	<ul class="breadcrumb">
		<li><a href="{page_path id='users'}?action=restore">[[Users]]</a></li>
		<li>[[Add a New User]]</li>
	</ul>
</div>
<div class="page-content choose-user-group">
	<div class="page-header">
		<h1>[[Choose User Group]]</h1>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<h4>[[Please select a user group for the new user]]:</h4>
			<div class="space-14"></div>
			<ul class="list-unstyled spaced">
				{foreach from=$user_groups_info item=user_group_info}
					<li class="bigger-120">
                        <i class="icon-double-angle-right"></i>
						<a href="?user_group_id={$user_group_info.id}">[[PhrasesInTemplates!{$user_group_info.name}]]</a>
					</li>
				{/foreach}
			</ul>
		</div>
	</div>
</div>

