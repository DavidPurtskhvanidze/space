<div class="breadcrumbs">
  <ul class="breadcrumb">
    <li><a href="{page_path module='miscellaneous' function='custom_settings'}">[[Custom Settings]]</a></li>
    <li>[[Edit Custom Setting]]</li>
  </ul>
</div>

<div class="page-content">
  <div class="page-header">
    <h1>[[Edit Custom Setting]]</h1>
  </div>
  
  <div class="row">
    {display_error_messages}
    {if $renderForm !== false}
      <div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60"></i>) are mandatory]]</div>
      <form method="post" class="form form-horizontal">
        {CSRF_token}
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="sid" value="{$custom_setting_info.sid}">
        <div class="form-group">
          <label class="control-label col-sm-3">
            [[ID]]
            <i class="icon-asterisk smaller-60"></i>
          </label>
          <div class="col-sm-6">
            <input type="text" name="id" value="{$custom_setting_info.id}" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-3">
            [[Value]]
            <i class="icon-asterisk smaller-60"></i>
          </label>
          <div class="col-sm-6">
            <input type="text" name="value" value="{$custom_setting_info.value}" class="form-control">
          </div>
        </div>
        <div class="clearfix form-actions">
          <input type="submit" value="[[Save:raw]]" class="btn btn-default">
        </div>
      </form>
    {/if}
  </div>
</div>
