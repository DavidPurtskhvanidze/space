
  <div class="row">
    {display_error_messages}
    <form method="post" enctype="multipart/form-data" class="form form-horizontal">
      {CSRF_token}
      <input type="hidden" name="action" value="import">
      <h4>[[Data Import]]</h4>
      <div class="form-group">
        <label class="control-label col-sm-3">[[File]]</label>
        <div class="col-sm-6">
          <input type="file" id="id-input-file" name="import_file" value="" class="form-control-file">
          <div class="help-block">
            [[IPImportDataFileInfo]]
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
            [[IPImportFileTypeInfo]]
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-3">[[Fields Delimiter]]:<br /><small>([[for CSV-file only]])</small></label>
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
      <div class="clearfix form-actions">
        <input type="submit" value="[[Import:raw]]" class="btn btn-default">
      </div>
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
