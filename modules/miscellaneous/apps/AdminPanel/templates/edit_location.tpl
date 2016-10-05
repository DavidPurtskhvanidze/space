<div class="breadcrumbs">
  <ul class="breadcrumb">
    <li><a href="{$GLOBALS.site_url}/geographic_data/">[[Geographic Locations]]</a></li>
    <li>[[Edit Geographic Location]]</li>
  </ul>
</div>
<div class="page-content">
  <div class="page-header">
    <h1>[[Geographic Locations]]</h1>
  </div>

  <div class="row">
    <h4>[[Edit Location Info]]</h4>

    {display_error_messages}
    {display_success_messages}

    <div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60"></i>) are mandatory]]</div>

    <form method="post" class="form form-horizontal">
      <input type="hidden" name="action" value="save_info">
      <input type="hidden" name="sid" value="{$location_sid}">
      {foreach from=$form_fields item=form_field}
          <div class="form-group">
            <label class="col-sm-3 control-label">
              [[$form_field.caption]]
              {if $form_field.is_required}<i class="icon-asterisk smaller-60 "></i>{/if}
            </label>
            <div class="col-sm-6">
              {input property=$form_field.id}
            </div>
          </div>
        {/foreach}
      <div class="clearfix form-actions">
        <input type="submit" value="[[Save:raw]]" class="btn btn-default">
      </div>
    </form>
  </div>
</div>
