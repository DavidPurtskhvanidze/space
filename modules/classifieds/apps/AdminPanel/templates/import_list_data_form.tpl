<div class="page-content">
    {display_error_messages}
	 <div class="row">
        <div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60"></i>) are mandatory]]</div>
        <form method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
            {CSRF_token}
            <input type="hidden" name="field_sid" value="{$field.sid}">
            <input type="hidden" name="action" value="import">
            <div class="form-group">
                <label class="col-sm-2 control-label">
                  [[File]]
                  <i class="icon-asterisk smaller-60"></i>
                </label>
                <div class="col-sm-8">
                    <input type="file" id="import-file" name="import_file" class="form-control-file">
                </div>
            </div>
            <br />
            <div class="form-group">
                <label class="col-sm-2 control-label">
                  [[File Type]]
                  <i class="icon-asterisk smaller-60"></i>
                </label>
                <div class="col-sm-8">
                    <select name="file_type" class="form-control">
                        <option value="lib\DataTransceiver\Import\InputDataSourceCSV" {if $REQUEST.file_type == 'lib\DataTransceiver\Import\InputDataSourceCSV'}selected{/if}>CSV</option>
                        <option value="lib\DataTransceiver\Import\InputDataSourceXLS" {if $REQUEST.file_type == 'lib\DataTransceiver\Import\InputDataSourceXLS'}selected{/if}>Excel</option>
                    </select>
                    <div class="hint">
                        [[IPImportFileTypeInfo]]
                    </div>
                </div>
            </div>
            <div class="clearfix form-actions">
                <input type="submit" value="[[Import:raw]]" class="btn btn-default">
            </div>
        </form>
   </div>
</div>

<script type="text/javascript">
    $('#import-file').ace_file_input({
        no_file:'[[No File ...]]',
        btn_choose:'[[Choose]]',
        btn_change:'[[Change]]',
        droppable:false,
        onchange:null,
        icon_remove: false,
        thumbnail:false, //| true | large
        blacklist:'exe|php|gif|png|jpg|jpeg'
    });
</script>
