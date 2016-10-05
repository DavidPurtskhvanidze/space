<div class="page-content">
  <div class="row">
    {display_error_messages}

    <div class="alert alert-warning">
      [[Import is not supported for the 'file' and 'video' field types.]]
    </div>

    <h4 class="headerBlue">[[System Import Values]]</h4>
    <form class="form form-horizontal" method="post" enctype="multipart/form-data">
      {CSRF_token}
      <div class="form-group">
        <label class="col-sm-3 control-label">[[Category]]</label>
        <div class="col-sm-6">
            <select name="category_sid" class="form-control">
              {foreach from=$categories item=category}
                {if $category.sid != 0}
                  <option value="{$category.sid}" {if $REQUEST.category_sid == $category.sid}selected{/if}>[[$category.name]]</option>
                {/if}
              {/foreach}
            </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">[[Listing Package]]</label>
        <div class="col-sm-6">
          <select name="listing_package" class="form-control">
            {foreach from=$packages item=package}
              <option value="{$package.sid}" {if $REQUEST.listing_package == $package.sid}selected{/if}>[[$package.name]]</option>
            {/foreach}
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">[[Check to activate all imported listings]]</label>
        <div class="col-sm-6">
          <label>
            <input type="checkbox" name="active" value="1" class="ace text" {if isset($REQUEST.active)}checked="checked"{/if}/>
            <span class="lbl"></span>
          </label>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">[[Set activation date]]</label>
        <div class="col-sm-6">
          {capture name="date_format_example" assign="date_format_example"}{tr type="date"}now{/tr}{/capture}
          {i18n->getDateFormat assign="date_format"}
          <input type="text" name="activation_date" value="{$REQUEST.activation_date}" class="form-control">
          <div class="help-block">[[date format: '$date_format', for example: '$date_format_example']]</div>
        </div>
      </div>
      <h4 class="headerBlue">[[Data Import]]</h4>
      <div class="form-group">
        <label class="col-sm-3 control-label">[[File]]</label>
        <div class="col-sm-6">
          <input type="file" name="import_file" value="" id="id-input-file" class="form-control-file">
            <div class="help-block">
              [[ImportDataFileInfo]]
            </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">[[File Type]]</label>
        <div class="col-sm-6">
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
        <label class="col-sm-3 control-label">[[Field Delimiter]]<br /><small>([[for CSV-file only]])</small></label>
        <div class="col-sm-6">
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
      <div class="form-group">
        <label class="col-sm-3 control-label">[[Not found values in DB will be]]</label>
        <div class="col-sm-6">
            <select name="non_existed_values" class="form-control">
              <option value="ignore" {if $REQUEST.non_existed_values == 'ignore'}selected{/if}>[[ignored]]</option>
              <option value="add" {if $REQUEST.non_existed_values == 'add'}selected{/if}>[[added to DB]]</option>
            </select>
        </div>
      </div>
      <div class="clearfix form-actions">
          <input type="hidden" name="action" value="import" />
          <input type="submit" value="[[Import:raw]]" class="btn btn-default">
      </div>
    </form>

    {require component="jquery" file="jquery.js"}
    <script type="text/javascript">
      $(document).ready(function()
      {
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
              $("td[id=freePackageFeatures] div[class='freeFeature " + key + "']").append($("<label></label>").attr("id", key).text(value));
            });
          }
        });
        $("select[name=listing_package]").change();
      });
    </script>
  </div>
</div>
<script type="text/javascript">
	$('#id-input-file').ace_file_input({
		no_file:'No File ...',
		btn_choose:'Choose',
		btn_change:'Change',
		droppable:false,
		onchange:null,
		icon_remove: false,
		thumbnail:false, //| true | large
		blacklist:'exe|php|gif|png|jpg|jpeg'
		//whitelist:'gif|png|jpg|jpeg'
		//onchange:''
		//
	});
</script>
