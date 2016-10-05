<div class="breadcrumbs">
  <ul class="breadcrumb">
    <li><a href="{page_path id='geographic_data'}">[[Geographic Locations]]</a></li>
    <li>[[Import Locations]]</li>
  </ul>
</div>

<div class="page-content">
  <div class="page-header">
    <h1>[[Import Locations]]</h1>
  </div>

  <div class="row">
    {display_success_messages}
    {display_error_messages}

    <div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60"></i>) are mandatory]]</div>

    <form method="post" class="form form-horizontal" enctype="multipart/form-data">
      {CSRF_token}
      <div class="form-group">
        <label class="control-label col-sm-3">
          [[File]]
          <i class="icon-asterisk smaller-60"></i>
        </label>
        <div class="col-sm-6">
          <input type="file" id="id-input-file" name="imported_geo_file" class="form-control-file">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-3">
          [[File Format]]
          <i class="icon-asterisk smaller-60"></i>
        </label>
        <div class="col-sm-6">
          <select name="file_format" class="form-control">
            <option value="csv">CSV</option>
            <option value="excel" {if $imported_file_config.file_format == excel}selected{/if}>Excel</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-3">
          [[Fields Delimiter]]:<br /><small>([[for CSV-file only]])</small>
          <i class="icon-asterisk smaller-60"></i>
        </label>
        <div class="col-sm-6">
          <select name="fields_delimiter" class="form-control">
            <option value="comma">[[Comma]]</option>
            <option value="tab"{if $imported_file_config.fields_delimiter == tab} selected{/if}>[[Tabulator]]</option>
            <option value="semicolumn"{if $imported_file_config.fields_delimiter == semicolumn} selected{/if}>[[Semicolon]]</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-3">
          [[Update on Match]]
        </label>
        <div class="col-sm-6">
          <label>
            <input class="ace" type="checkbox" name="update_on_match" value="1" {if $imported_file_config.update_on_match}checked="checked"{/if}>
            <span class="lbl"></span>
          </label>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-3">
          [[Start Line]]
          <i class="icon-asterisk smaller-60"></i>
        </label>
        <div class="col-sm-6">
          <input type="text" name="start_line" value="{$imported_file_config.start_line}" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-3">
          [[Location ID Column]]
          <i class="icon-asterisk smaller-60"></i>
        </label>
        <div class="col-sm-6">
          <input type="text" name="name_column" value="{$imported_file_config.name_column}" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-3">
            [[Latitude Column]]
            <i class="icon-asterisk smaller-60"></i>
        </label>
        <div class="col-sm-6">
            <input type="text" name="latitude_column" value="{$imported_file_config.latitude_column}" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-3">
          [[Longitude Column]]
          <i class="icon-asterisk smaller-60"></i>
        </label>
        <div class="col-sm-6">
          <input type="text" name="longitude_column" value="{$imported_file_config.longitude_column}" class="form-control">
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
