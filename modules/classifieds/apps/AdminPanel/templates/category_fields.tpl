{include file="miscellaneous^dialog_window.tpl"}
<div class="categoryFields">
	<div class="breadcrumbs">
		<ul class="breadcrumb">
			{foreach from=$ancestors item=ancestor}
				<li><a href="{page_path id='edit_category'}?sid={$ancestor.sid}">[[$ancestor.caption]]</a></li>
			{/foreach}
			<li>[[Category Fields]]</li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-header">
			<h1 class="lighter">{if $is_root_category}[[Common Fields]]{else}[[Category Fields]]{/if}</h1>
			<br/>

			<div class="alert alert-warning">
				[[Here you can edit fields that are the same for the current listing category and all its subcategories.
				To edit fields that are specific to a category, please click Edit Category Fields against the
				category.]]
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				{assign var="ancestorCaption" value=$ancestor.caption}
				<a class="btn btn-link"
				   href="{page_path id='add_category_field'}?category_sid={$category_sid}">{if $is_root_category || $ancestor.caption eq 'Categories'}[[Add a New Common Field]]{else}[[Add a New  Field for the '$ancestorCaption' Category]]{/if}</a>
				<br/>
				<a class="btn btn-link"
				   href="{page_path id='add_category_field_bulk'}?category_sid={$category_sid}&raw_output=1"
				   onclick="return openDialogWindow('Bulk Field Creation', this.href, 800)">{if $is_root_category || $ancestor.caption eq 'Categories'}[[Bulk Field Creation]]{else}[[Bulk Field Creation for the '$ancestorCaption' Category]]{/if}</a>
				<br/>
				{display_success_messages}
				{display_error_messages}

				{if $listing_fields|@count > 0}
					<div class="dataTables_wrapper" role="grid">
						<div class="row">
							<div class="col-xs-12 rightText">
								<div class="btn-group">
									<a href="#" class="btn btn-primary dropdown-toggle btn-xs actionWithSelected"
									   data-toggle="dropdown">
										[[Actions with selected]]
										<i class="icon-angle-down icon-on-right"></i>
									</a>
									<ul class="dropdown-menu dropdown-info pull-right actionList">
										<li>
											<a onclick="return submitItemSelectorForm(this, '[[Are you sure that you want to delete this field?:raw]]')"
											   href="{page_path id='delete_category_field'}">[[Delete]]</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<form method="post" name="itemSelectorForm">
                                {CSRF_token}
								<table class="items sortable table table-striped table-hover"
								       data-parent-value="{$ancestor.sid}"
								       data-sorting-url="{page_path module='classifieds' function='move_category_field'}?category_sid={$category_sid}">
									<thead>
									<tr class="head">
										<th class="center">
											<label>
												<input class="ace" type="checkbox">
												<span class="lbl"></span>
											</label>
										</th>
										<th>[[ID]]</th>
										<th class="hidden-sm hidden-xs">[[Caption]]</th>
										<th>[[Type]]</th>
										<th class="hidden-xs">[[Required]]</th>
										<th colspan="2">[[Actions]]</th>
									</tr>
									</thead>
									<tbody>
									{foreach from=$listing_fields item=listing_field name=items_block}
										{assign var="field_sid" value=$listing_field.sid}
										{assign var="checkBoxPram" value=''}
										{if $checkedSids.$field_sid}
											{assign var="checkBoxPram" value='checked="checked" '}
										{/if}
										<tr data-item-sid="{$listing_field.sid}">
											<td class="align-middle center">
												{if $category_sid == $listing_field.category_sid}
													<label>
														<input type="checkbox" class="ace"
														       name="sids[{$field_sid}]"
														       value="{$field_sid}"
														       id="checkbox_{$smarty.foreach.items_block.iteration}" {$checkBoxPram}/>
														<span class="lbl"></span>
													</label>
												{else}
													&nbsp;
												{/if}
											</td>
											<td>{$listing_field.id}</td>
											<td class="hidden-sm hidden-xs">{$listing_field.caption}</td>
											<td>{$listing_field.type}</td>
											<td class="hidden-xs">{if $listing_field.is_required.isTrue}[[Yes]]{else}[[No]]{/if}</td>
											{if $category_sid == $listing_field.category_sid}
												<td>
													<div class="btn-group">
														<a class="itemControls edit btn btn-xs btn-info"
														   href="{page_path id='edit_category_field'}?sid={$listing_field.sid}"
														   title="Edit" data-rel="tooltip"
														   data-original-title="[[Edit]]"><i
																	class="icon-edit bigger-120"></i></a>
														<a class="itemControls delete btn btn-xs btn-danger"
														   href="{page_path id='delete_category_field'}?sids[{$listing_field.sid}]={$listing_field.sid}"
														   onclick='return confirm("[[Are you sure that you want to delete this field?:raw]]")'
														   title="[[Delete]]" data-rel="tooltip"
														   data-original-title="[[Delete]]"><i
																	class="icon-trash bigger-120"></i></a>
													</div>
												</td>
											{else}
												<td>
													<a href="{page_path module='classifieds' function='category_fields'}?sid={$listing_field.category_sid}">
														[[Parent field]]
													</a>
												</td>
											{/if}
											<td class="sort">
						                  <span title="[[Drag and drop to change the order:raw]]">
						                    <i class="icon-sort"></i>
						                  </span>
											</td>
										</tr>
									{/foreach}
									</tbody>
								</table>
							</form>
						</div>
					</div>
				{/if}
			</div>
		</div>
	</div>
</div>
{require component="jquery" file="jquery.js"}
{require component="jquery-ui" file="jquery-ui.js"}
{require component="jquery-ui" file="css/smoothness/jquery-ui.css"}
<script type="text/javascript">
	var noSelectedItemsMessage = "[[You have not selected any items. Please select one or more items and proceed with actions.:raw]]";

	function submitItemSelectorForm(anchor, confirmationMessage) {
		if (confirmationMessage && !confirm(confirmationMessage)) {
			return false;
		}
		window.location.href = $(anchor).attr("href") + "?" + $("form[name='itemSelectorForm']").serialize();
		return false;
	}

	$(document).ready(function () {
		$(".actionWithSelected").click(function () {
			if (!$('input[name^=sids]:checked').length) {
				$(this).addClass("disabled");
				alert(noSelectedItemsMessage);
			}
		});

		$('.table tr input:checkbox').on('change', function () {
			if ($(this).prop("checked")) {
				$(".actionWithSelected").removeClass("disabled");
			}
		});

		$('table th input:checkbox').on('click', function () {
			var that = this;
			$(this).closest('table').find('tr > td:first-child input:checkbox')
					.each(function () {
						this.checked = that.checked;
						$(this).closest('tr').toggleClass('selected');
					});
		});
	});

</script>
{include file="miscellaneous^sortable_js.tpl"}
