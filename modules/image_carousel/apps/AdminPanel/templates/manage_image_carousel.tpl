<div class="imageCarousel">
<div class="breadcrumbs">
	<ul class="breadcrumb">
		<li>[[Image Carousel]]</li>
	</ul>
</div>
<div class="page-content">
{display_success_messages}
{display_error_messages}
<div class="page-header">
	<h3 class="lighter blue">[[Images]]</h3>
</div>
<a class="btn btn-link" href="{page_path module='image_carousel' function='add_carousel_image'}">[[Add new
	image]]</a>
<br/>

<div class="row">
	<div class="col-xs-12">
		<div class="table-responsive">
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
									<a onclick="return submitItemSelectorForm(this, '[[Are you sure that you want to delete selected carousel images?:raw]]')"
									   href="?action=Delete">[[Delete]]</a>
								</li>

								<li>
									<a onclick="return submitItemSelectorForm(this, '[[Are you sure?:raw]]')"
									   href="?action=change_status&status=enabled" class="status">[[Enable]]</a>
								</li>
								<li>
									<a onclick="return submitItemSelectorForm(this, '[[Are you sure?:raw]]')"
									   href="?action=change_status&status=disabled" class="status">[[Disable]]</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<form method="post" name="itemSelectorForm">
                    {CSRF_token}
					<table class="dataTable items sortable table table-striped table-hover"
					       data-sorting-url="{page_path module='image_carousel' function='manage_image_carousel'}">
						<thead>
						<tr class="head">
							<th class="center">
								<label>
									<input class="ace" type="checkbox">
									<span class="lbl"></span>
								</label>
							</th>
							<th>[[Image]]</th>
							<th>[[Caption]]</th>
							<th>[[URL]]</th>
							<th colspan="2">[[Actions]]</th>
						</tr>
						</thead>
						<tbody>
						{foreach from=$images item=image name=items_block}
							{assign var="checkBoxPram" value=''}
							{if $checkedImages[$image.sid|cat:'']}
								{assign var="checkBoxPram" value='checked="checked" '}
							{/if}
							<tr class="{cycle values="odd,even"}" data-item-sid="{$image.sid}">
								<td class="align-middle center">
									<label>
										<input type="checkbox" class="ace" name="images[{$image.sid}]"
										       value="{$image.sid}"
										       id="checkbox_{$smarty.foreach.items_block.iteration}" {$checkBoxPram}/>
										<span class="lbl"></span>
									</label>
								</td>
								<td>
									<img src="{$image.image.thumbnail.url}" width="100"/>
								</td>
								<td>{$image.caption}</td>
								<td>
									<a href="{if strpos($image.url, '/') === 0}{$frontEndSiteUrl}{/if}{$image.url}">{$image.url}</a>
								</td>
								<td>
									<div class="btn-group">
										{if $image.disabled.isTrue}
											<a class="itemControls enable btn btn-xs btn-success"
											   href="{page_path module='image_carousel' function='manage_image_carousel'}?action=change_status&images[{$image.sid}]={$image.sid}&status=enabled"
											   title="[[Enable:raw]]">
												<i class="icon-off"></i>
											</a>
										{else}
											<a class="itemControls disable btn btn-xs btn-inverse"
											   href="{page_path module='image_carousel' function='manage_image_carousel'}?action=change_status&images[{$image.sid}]={$image.sid}&status=disabled"
											   title=[[Disable:raw]]>
												<i class="icon-off"></i>
											</a>
										{/if}
										<a class="itemControls edit btn btn-xs btn-info"
										   href="{page_path module='image_carousel' function='edit_carousel_image'}?image_sid={$image.sid}"
										   title="[[Edit:raw]]">
											<i class="icon-edit"></i>
										</a>
										<a class="itemControls delete btn btn-xs btn-danger"
										   href="{page_path module='image_carousel' function='manage_image_carousel'}?action=delete&images[{$image.sid}]={$image.sid}"
										   onclick="return confirm('[[Are you sure you want to delete this carousel image?:raw]]')"
										   title="[[Delete:raw]]">
											<i class="icon-trash"></i>
										</a>
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
				</form>
			</div>
		</div>


	</div>
</div>
<br/>
<br/>
<div class="row">
	<div class="col-xs-12">
		<div class="widget-box no-border collapsed">
			<div class="widget-header header-color-dark">
				<h4 class="white" title="[[Click to hide the search form:raw]]">
					<a data-action="collapse" href="#">
						<i class="icon-chevron-up"></i> [[Carousel Settings]]
					</a>
				</h4>
			</div>
			<div class="widget-body">
				<div class="widget-main padding-6 no-padding-left no-padding-right">
					<div class="alert alert-info">[[Valid only for non-bootstrap themes]]</div>
					<form method="post" class="form-horizontal" role="form">
                        {CSRF_token}
						<input type="hidden" name="action" value="save">

						<div class="form-group">
							<label class="col-sm-3 control-label">
								[[Width (in px)]]
							</label>

							<div class="col-sm-8">
								<input type="text" name="image_carousel_width"
								       value="{$settings.image_carousel_width}"
								       class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">
								[[Height (in px)]]
							</label>

							<div class="col-sm-8">
								<input type="text" name="image_carousel_height"
								       value="{$settings.image_carousel_height}"
								       class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">
								[[Transition time (in sec.)]]
							</label>

							<div class="col-sm-8">
								<input type="text" name="image_carousel_transition_time"
								       value="{$settings.image_carousel_transition_time}" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">
								[[Show arrows]]
							</label>

							<div class="col-sm-8">
								<div class="checkbox">
									<input type="hidden" name="image_carousel_show_arrows" value="0">
									<label>
										<input class="ace  ace-switch ace-switch-6" type="checkbox"
										       name="image_carousel_show_arrows"
										       value="1"{if $settings.image_carousel_show_arrows} checked{/if}>
										<span class="lbl"></span>
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">
								[[Show image numbers]]
							</label>

							<div class="col-sm-8">
								<div class="checkbox">
									<input type="hidden" name="image_carousel_show_numbers" value="0">
									<label>
										<input class="ace  ace-switch ace-switch-6" type="checkbox"
										       name="image_carousel_show_numbers"
										       value="1"{if $settings.image_carousel_show_numbers} checked{/if}>
										<span class="lbl"></span>
									</label>
								</div>
							</div>
						</div>
						<div class="clearfix form-actions">
							<input type="submit" value="[[Save:raw]]" class="btn btn-default">
						</div>
					</form>

				</div>
			</div>
			{include file="miscellaneous^toggle_search_form_js.tpl"}
		</div>
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
		window.location.href = $(anchor).attr("href") + "&" + $("form[name='itemSelectorForm']").serialize();
		return false;
	}

	$(document).ready(function () {
		$(".actionWithSelected").click(function () {
			if (!$('input[name^=images]:checked').length) {
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
