<div class="page-content">
  <div class="row">
    {display_error_messages}

    <form class="form form-horizontal" method="post"  enctype="multipart/form-data">
      {CSRF_token}
      <h4 class="headerBlue">[[System Import Values]]</h4>

      <div class="form-group">
        <label class="control-label col-sm-3">[[User Group]]</label>
        <div class="col-sm-6">
          <select name="user_group_sid" class="form-control">
            <option value="">[[Choose User Group]]</option>
            {foreach from=$user_groups item=user_group}
              <option value="{$user_group.sid}" {if $REQUEST.user_group_sid == $user_group.sid}selected{/if}>[[$user_group.name]]</option>
            {/foreach}
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-3">[[Check to activate all imported users]]</label>
        <div class="col-sm-6">
            <input type="hidden" name="activate" value="0">
          <label>
            <input class="ace ace-switch ace-switch-6" type="checkbox" name="activate" value="1" {if isset($REQUEST.activate)}checked="checked"{/if} />
            <span class="lbl"></span>
          </label>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-3">[[Check to activate all notifications]]</label>
        <div class="col-sm-6">
            <input type="hidden" name="notifications" value="0">
          <label>
            <input class="ace ace-switch ace-switch-6" type="checkbox" name="notifications" value="1" {if isset($REQUEST.notifications)}checked="checked"{/if} />
            <span class="lbl"></span>
          </label>
        </div>
      </div>

      <h4 class="headerBlue">[[Data Import]]</h4>

      <div class="form-group">
        <label class="col-sm-3 control-label">[[File]]</label>
        <div class="col-sm-6">
          <input type="file" id="id-input-file" name="import_file" value="" class="form-control-file">
          <div class="help-block">
            [[ImportUserDataFileInfo]]
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-sm-3">[[File Type]]</label>
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
        <label class="control-label col-sm-3">[[Field Delimiter]]<br /><small>([[for CSV-file only]])</small></label>
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
        <label class="control-label col-sm-3">[[Not found values in DB will be]]</label>
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

      </table>
    </form>
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
