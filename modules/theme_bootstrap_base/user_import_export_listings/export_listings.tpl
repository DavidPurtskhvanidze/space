<div class="userExportListings form">
	<h1>[[Export Listings]]</h1>

	<div class="alert alert-warning">
		[[You can export some or all listings in an Excel file format (csv export is not supported). The resulting
		archive will contain all listings and all files associated with them.]]
	</div>
	{display_error_messages}
	<form method="post" enctype="multipart/form-data" class="form-horizontal">
		<input type="hidden" name="action" value="export">

		<fieldset>
			<legend>[[Export Filter]]</legend>

			<div class="form-group">
				<label class="col-sm-2 control-label">[[Listing ID]]</label>

				<div class="col-sm-10">{search property="id"}</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">[[Category]]</label>

				<div class="col-sm-10">{search property="category_sid" template="user_import_export_listings^search/category_tree.tpl"}</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">[[Activation Date]]</label>

				<div class="col-sm-10">{search property="activation_date"}</div>
			</div>
		</fieldset>

		<div class="form-group">
			<label class="col-sm-2 control-label">[[Listing Properties To Export]]</label>

			<div class="col-sm-10">
				<div class="row">
					{foreach from=$properties item=property name=properties}
						<div class="col-xs-6 col-sm-4 col-md-3">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="export_properties[]" value="{$property.id}"/>
									[[FormFieldCaptions!{$property.caption}]]
								</label>
							</div>
						</div>
					{/foreach}
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<ul class="propertyControls">
					{strip}
						<li class="propertyControl SelectAll"><a href="#">[[Select all]]</a></li>
						<li class="propertyControl DeselectAll"><a href="#">[[Deselect all]]</a></li>
					{/strip}
				</ul>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<input class="btn btn-primary" type="submit" value="[[Export:raw]]"/>
			</div>
		</div>

	</form>
</div>
{require component="jquery" file="jquery.js"}
<script type="text/javascript">
	$(function () {
		$('.propertyControl.SelectAll a').click(function (e) {
			$('input[name^=export_properties]').prop("checked", true);
			return false;
		});
		$('.propertyControl.DeselectAll a').click(function (e) {
			$('input[name^=export_properties]').prop("checked", false);
			return false;
		});
	});
</script>
