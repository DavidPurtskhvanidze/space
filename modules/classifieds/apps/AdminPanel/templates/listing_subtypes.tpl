{include file="miscellaneous^dialog_window.tpl"}
<div class="categories">
	<div class="breadcrumbs">
		<ul class="breadcrumb">
			{foreach from=$ancestors item=ancestor name=ancestor_cycle}
				{if $smarty.foreach.ancestor_cycle.last}
					<li>[[$ancestor.caption]]</li>
				{else}
					<li><a href="{page_path id='edit_category'}?sid={$ancestor.sid}">[[$ancestor.caption]]</a></li>
				{/if}
			{/foreach}
		</ul>
	</div>

	<div class="page-content">
		<div class="page-header">
			{assign var="categoryCaption" value=$category.caption}
			{if $category.sid ne 0}
				<h1>[[$categoryCaption Category]]</h1>
			{/if}
		</div>
		<div class="row">
			{if $category.sid ne 0}
				<a class="btn btn-link" href="{page_path module='classifieds' function='category_settings'}?sid={$category.sid}">[[Edit '$categoryCaption' Category Settings]]</a>
				<a class="btn btn-link" href="{page_path module='classifieds' function='category_fields'}?sid={$category.sid}">[[Edit '$categoryCaption' Category Fields]]</a>
			{else}
				<a class="btn btn-link" href="{page_path module='classifieds' function='category_settings'}?sid={$category.sid}">[[Edit Root Category Settings]]</a>
				<div class="alert alert-warning">
					[[The root category is the main category for all subcategories. You cannot add listings to the root category. Our categories are structured hierarchically. This means that settings of the root category are inherited by all subcategories if subcategories do not have their own settings.]]
				</div>
				<a class="btn btn-link" href="{page_path module='classifieds' function='category_fields'}?sid={$category.sid}">[[Edit Common Fields]]</a>
				<div class="alert alert-warning">
					[[Common fields are listing fields which are the same for all categories and subcategories.]]
				</div>
			{/if}
		</div>
		<div class="row">

			<h4 class="lighter">{if $category.sid eq 0}[[Categories]]{else}[[Subcategories]]{/if}</h4>

			<a class="btn btn-link" href="{page_path id='add_category'}?parent={$category.sid}">{if $category.sid eq 0}[[Add a New Category]]{else}[[Add a New Subcategory to This Level]]{/if}</a>

			<a class="btn btn-link" href="{page_path id='add_category_bulk'}?parent={$category.sid}&raw_output=1" onclick="return openDialogWindow('Add Bulk Category', this.href, window.innerWidth)">[[Bulk Category Creation]]</a>

			<div class="col-xs-12">
				{display_error_messages}
				{display_success_messages}
				{if $categories}
					<table id="categoryList" class="items sortable table table-striped table-hover" data-parent-value="{$category.sid}" data-sorting-url="{page_path module='classifieds' function='move_category'}">
						<thead>
							<tr>
								<th>[[ID]]</th>
								<th>[[Name]]</th>
								<th>[[Number of <br />Listings]]</th>
								<th>[[Number of <br />Subcategories]]</th>
								<th colspan="5">[[Actions]]</th>
							</tr>
						</thead>
						<tbody>
						{foreach from=$categories item=category name=items_block}
							{assign var="categoryCaption" value=$category.caption}
							<tr data-item-sid={$category.sid}>
								<td><a href="{page_path id='edit_category'}?sid={$category.sid}" title="[[$categoryCaption Subcategories:raw]]" data-item-id="{$category.id}" class="dataItemId">{$category.id}</a></td>
								<td>[[$category.caption]]</td>
								<td>{$category.listing_number}</td>
								<td>{$category.N_children}</td>
								<td>
									<div class="btn-group">
										<a class="itemControls editCategoryFields btn btn-xs btn-primary" href="{page_path module='classifieds' function='category_fields'}?sid={$category.sid}" title="[[Edit Category Fields:raw]]">
											<i class="icon-list"></i>
										</a>
										<a class="itemControls btn btn-xs btn-warning" href="{page_path module='classifieds' function='relocate_listing_category'}?relocating_category_sid={$category.sid}" target="_blank" onclick='return openDialogWindow("[[Relocate Category]]", this.href, 500)' title="Relocate Category">
											<i class="icon-exchange"></i>
										</a>
										<a class="itemControls edit btn btn-xs btn-info" href="{page_path module='classifieds' function='category_settings'}?sid={$category.sid}" title="[[Edit:raw]]">
											<i class="icon-edit"></i>
										</a>
										{if $category.listing_number == 0}
											<a class="itemControls delete btn btn-xs btn-danger" href="{page_path id='delete_category'}?sid={$category.sid}" onclick='return confirm("[[Are you sure that you want to delete this category?:raw]]")' title="[[Delete $categoryCaption Category]]">
												<i class="icon-trash"></i>
											</a>
										{/if}
									</div>
								</td>
								<td class="sort">
									<span title="[[Drag and drop to change the order:raw]]">
										<i class="icon-sort"></i>
									</span>
								</td>
							</tr>
						{/foreach}
						</tbody>
					</table>
				{/if}
			</div>
		</div>
	</div>

</div>
{include file="miscellaneous^sortable_js.tpl"}
