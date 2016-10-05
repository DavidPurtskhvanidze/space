<div class="importTreeValues">
	<div class="breadcrumbs">
         <ul class="breadcrumb">
            <li>{foreach from=$ancestors item=ancestor}
			<a href="{page_path id='edit_category'}?sid={$ancestor.sid}">[[$ancestor.caption]]</a> &gt;
		{/foreach}
		{if $field.category_sid}
			<a href="{page_path id='edit_category_field'}?sid={$field_sid}">[[$field.caption]]</a> &gt;
		{else}
			<a href="{page_path id='edit_listing_field'}?sid={$field_sid}">[[$field.caption]]</a> &gt;
		{/if}
		<a href="{page_path id='edit_listing_field_edit_tree'}?field_sid={$field_sid}">[[Edit Tree]]</a> &gt;
		[[Import Tree Data]]
            </li>
        </ul>		
	</div>
    <div class="page-content">
        <div class="page-header">
            <h1 class="lighter">[[Import Tree Data]]</h1>
        </div>
        <div class="alert alert-warning">
            [[Please note that for the Excel format you can import .xls files of the Ms Office versions 95, 97, 2000 and 2003; and you cannot import .xlsx files.]]
        </div>
         <div class="row">
            {display_error_messages}
            <div class="alert alert-info">
                [[Fields marked with an asterisk (<span class="asterisk">*</span>) are mandatory]]
            </div>
            <form method="post" class="form-horizontal" enctype="multipart/form-data">
                {CSRF_token}
                <input type="hidden" name="field_sid" value="{$field.sid}">
                <input type="hidden" name="action" value="import">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">
                            [[File]]
                            <i class="icon-asterisk smaller-60  smaller-60 "></i>
                        </label>
                        <div class="col-sm-8">
                            <input type="file" id="id-input-file-2" name="imported_tree_file" class="form-control-file">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">
                            [[File Format]]
                            <i class="icon-asterisk smaller-60  smaller-60 "></i>
                        </label>
                        <div class="col-sm-8">
                             <select name="file_format" class="form-control">
                                <option value="csv">[[CSV]]</option>
                                <option value="excel" {if $imported_file_config.file_format == excel}selected{/if}>[[Excel]]</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">
                            [[Start Line]]
                            <i class="icon-asterisk smaller-60  smaller-60 "></i>
                        </label>
                        <div class="col-sm-8">
                             <input type="text" name="start_line" value="{$imported_file_config.start_line}" class="form-control">
                        </div>
                    </div>
                    <div class="clearfix form-actions">
                        <input type="submit" value="[[Import:raw]]" class="btn btn-default">
                    </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
$('#id-input-file-2').ace_file_input({
					no_file:'No File ...',
					btn_choose:'Choose',
					btn_change:'Change',
					droppable:false,
					onchange:null,
					thumbnail:false //| true | large
					//whitelist:'gif|png|jpg|jpeg'
					//blacklist:'exe|php'
					//onchange:''
					//
				});
</script>
