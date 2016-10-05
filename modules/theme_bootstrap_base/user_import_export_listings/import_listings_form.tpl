	<div class="alert alert-warning">
		[[Import is not supported for the 'file' and 'video' field types.]]
	</div>
	<div class="alert alert-warning">
		[[The listing fields which do not exist for a category will be ignored while importing data, the missing tree and list-type values will be left blank after the import. For example, the field "Price" does not exit for the category "For Rent", thus price values will be ignored when importing listing records in any case (either they are filled or not in the file). If you add an item with the "Navy" color, and this color does not exist on the website, the option "Color" will be left empty for this listing after the import.]]
	</div>
	<form class="form-horizontal" method="post" enctype="multipart/form-data">
        {CSRF_token}
		<input type="hidden" name="action" value="import" />
		<fieldset>
			<legend>[[System Import Values]]</legend>
			<div class="form-group">
				<label class="col-sm-2 control-label">[[Category]]</label>
				<div class="col-sm-10">
					<select name="category_sid" class="form-control">
						{foreach from=$categories item=category}
							{if $category.sid != 0}
								<option value="{$category.sid}" {if $REQUEST.category_sid == $category.sid}selected{/if}>[[$category.name]]</option>
							{/if}
						{/foreach}
					</select>
					<div class="help-block">
						[[Please select a category from the list. Imported listings will belong to the chosen listing type.<br />If you plan to import listings into several categories, please ignore this parameter — each listing record from the importing file will be added to the category, specified for this record in the importing file.<br />In case the category is not specified for the listing record in the file, this listing will added to the selected category.<br />If you specify the category which does not exist, or you mistyped the category ID, the listing will be created in the default category as well.]]
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">[[Listing Package]]</label>
				<div class="col-sm-10">
					<select name="listing_package" class="form-control">
						{foreach from=$packages item=package}
							<option value="{$package.sid}" {if $REQUEST.listing_package == $package.sid}selected{/if}>[[$package.name]]</option>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"></label>
				<div id="freePackageFeatures"></div>
			</div>
		</fieldset>
		<fieldset>
			<legend>[[Data Import]]</legend>
			<div class="form-group">
				<label class="col-sm-2 control-label">[[File]]</label>
				<div class="col-sm-10">
					<input type="file" id="import_file" name="import_file" value="" class="text" />
					<div class="help-block">
						[[ImportDataFileInfo]]
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">[[File Type]]</label>
				<div class="col-sm-10">
					<select name="file_type" class="form-control">
						<option value="lib\DataTransceiver\Import\InputDataSourceCSV" {if $REQUEST.file_type == 'lib\DataTransceiver\Import\InputDataSourceCSV'}selected{/if}>CSV</option>
						<option value="lib\DataTransceiver\Import\InputDataSourceXLS" {if $REQUEST.file_type == 'lib\DataTransceiver\Import\InputDataSourceXLS'}selected{/if}>Excel</option>
					</select>
					<div class="help-block">
						[[ImportFileTypeInfo]]
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">[[Field Delimiter]]<br /><small>([[for CSV-file only]])</small></label>
				<div class="col-sm-10">
					<select name="csv_delimiter" class="form-control">
						<option value="comma" {if $REQUEST.csv_delimiter == 'comma'}selected{/if}>[[Comma]]</option>
						<option value="tab" {if $REQUEST.csv_delimiter == 'tab'}selected{/if}>[[Tabulator]]</option>
						<option value="colon" {if $REQUEST.csv_delimiter == 'colon'}selected{/if}>[[Colon]]</option>
						<option value="semicolon" {if $REQUEST.csv_delimiter == 'semicolon'}selected{/if}>[[Semicolon]]</option>
						<option value="pipe" {if $REQUEST.csv_delimiter == 'pipe'}selected{/if}>[[Pipe]]</option>
						<option value="dot" {if $REQUEST.csv_delimiter == 'dot'}selected{/if}>[[Dot]]</option>
					</select>
				</div>
			</div>
		</fieldset>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<input type="submit" class="btn btn-primary" value="[[Import:raw]]" />
			</div>
		</div>

	</form>
</div>
{require component="jquery" file="jquery.js"}
<script type="text/javascript" src="{url file="field_types^ace-elements.min.js"}"></script>
<script type="text/javascript">
	$(document).ready(function()
	{
		$('#import_file').ace_file_input({
			no_file:'[[No File ...]]',
			btn_choose:'[[Choose]]',
			btn_change:'[[Change]]',
			droppable:false,
			onchange:null,
			icon_remove: false,
			thumbnail:false, //| true | large
			blacklist:'csv|xls|zip|tar.gz'
		});

		var freePackageFeaturesData = {
			{foreach from=$freePackageFeatures key=packageSid item=features name=packagesBlock}
				{$packageSid}:{strip}
				{
					{foreach from=$features item=feature name=featuresBlock}
						{capture name=itemCaption}[[$feature.caption]]{/capture}
						'{$feature.name}' : '{$smarty.capture.itemCaption|addcslashes:"\'\\\/"}'{if $smarty.foreach.featuresBlock.iteration < $smarty.foreach.featuresBlock.total},{/if}
					{/foreach}
				}
				{if $smarty.foreach.packagesBlock.iteration < $smarty.foreach.packagesBlock.total},{/if}{/strip}
			{/foreach}
		};

		$("select[name=listing_package]").change(function()
		{
			var count = 0;
			$.each(freePackageFeaturesData[$(this).val()], function(key, value) { count+=1; } );
			if (count == 0)
			{
				$("td[id=freePackageFeatures]").html("[[The selected Listing Package does not have free listing features for activation.]]");
			}
			else
			{
				$("td[id=freePackageFeatures]").html("");
				$.each(freePackageFeaturesData[$(this).val()], function(key, value)
				{
					$("td[id=freePackageFeatures]").append($("<div></div>").attr("class", "freeFeature " + key));
					$("td[id=freePackageFeatures] div[class='freeFeature " + key + "']").append($("<input></input>").attr("type", "checkbox").attr("name", "included_features[]").attr("value", key).attr("id", key));
					$("td[id=freePackageFeatures] div[class='freeFeature " + key + "']").append($("<label></label>").attr("for", key).text(value));
				});
			}
		});
		$("select[name=listing_package]").change();
	});
</script>
