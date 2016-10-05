<div class="breadcrumbs">
	<ul class="breadcrumb">
		<li>[[Publications]]</li>
	</ul>
</div>
<div class="page-content">
	<div class="page-header">
		<h1 class="lighter">[[Publications]]</h1>
	</div>
	{display_error_messages}

	<h4 class="headerBlue">[[Add a New Section]]</h4>

	{display_error_messages}
	{include file="publication_category_form.tpl" action="add"}

	<div class="row">
		<div class="col-xs-12">
			<table class="items sortable table table-striped table-hover">
				<thead>
				<tr class="head">
					<th>[[ID]]</th>
					<th>[[Section Name]]</th>
					<th colspan=6>[[Actions]]</th>
				</tr>
				</thead>
				<tbody>
				{foreach from=$categories item="category" name="foreach"}
					<tr data-item-sid="{$category.sid}">
						<td>{$category.id}</td>
						<td>[[{$category.title|escape}]]</td>
						<td><a class="itemControls addArticle" href="{page_path module='publications' function='add_article'}?category_sid={$category.sid}" title="[[Add Article:raw]]">[[Add Article]]</a></td>
						<td><a class="itemControls createPage" href="{page_path module='site_pages' function='add_site_page'}?module=publications&function=show_publications&parameters%5Bcategory_id%5D={$category.id}&application_id=FrontEnd"
						       title="[[Create Page:raw]]">[[Create Page]]</a></td>
						<td><a class="itemControls edit" href="{page_path module='publications' function='edit_category'}?category_sid={$category.sid}" title="[[Edit:raw]]">[[Edit]]</a></td>
						<td><a class="itemControls delete" href="?action=delete_category&category_sid={$category.sid}" onclick="return confirm('[[Are you sure you want to delete section?:raw]]')" title="[[Delete:raw]]">[[Delete]]</a></td>
						<td class="sort">
							<span title="[[Drag and drop to change the order:raw]]">
								<i class="icon-sort"></i>
							</span>
						</td>
					</tr>
				{/foreach}
				</tbody>
			</table>
		</div>
	</div>
</div>
{include file="miscellaneous^sortable_js.tpl"}
