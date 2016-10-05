{function name=generateSortLink field="id" caption='ID'}
{strip}
	{foreach $sortingField as $sortFieldKey => $sortFieldValue}
		<a href="?sorting_fields[{$field}]={if $sortFieldKey eq $field}{$sortFieldValue}{else}ASC{/if}">
			[[{$caption}]]
			{if $sortFieldKey eq $field}
				&nbsp;&nbsp;&nbsp;&nbsp;<i class="arrow {if $sortFieldValue eq 'ASC'}icon-angle-down{else}icon-angle-up{/if}"></i>
			{/if}
		</a>
	{/foreach}
{/strip}
{/function}

<div class="editListValues">
	<div class="breadcrumbs">
		<ul class="breadcrumb">
			<li>[[Email Templates]]</li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-header">
			<h1>[[Email Templates]]</h1>
		</div>
		{display_error_messages}
		{display_success_messages}
		<div>
			<table class="table">
				<thead>
					<tr>
						<th>{generateSortLink field="id" caption="ID"}</th>
						<th>{generateSortLink  field="caption" caption="Description"}</th>
						<th>[[Action]]</th>
					</tr>
				</thead>
				<tbody>
					{foreach $emailTemplates as $template}
						<tr>
							<td>{$template.id}</td>
							<td>[[$template.caption:raw]]</td>
							<td>
								<a href="{page_path module='main' function='email_templates_edit'}?id={$template.id}"
								   class="btn btn-xs btn-info"
								   title="[[Edit:raw]]">
									<i class="icon-edit"></i>
								   </a>
							</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
	</div>
</div>
