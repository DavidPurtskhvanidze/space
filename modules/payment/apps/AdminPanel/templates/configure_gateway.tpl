<div class="breadcrumbs">
  <ul class="breadcrumb">
    <li><a href="{page_path module='payment' function='gateways'}">[[Payment Gateways]]</a></li>
    <li>{$gateway->getCaption()}</li>
  </ul>
</div>

<div class="page-content">
  <div class="page-header">
    {assign var="gatewayCaption" value=$gateway->getCaption()}

    <h1>[[Configure $gatewayCaption]]</h1>
  </div>
  <div class="row">
    {display_error_messages}
    {display_success_messages}

    <div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60"></i>) are mandatory]]</div>

    <form method="post" class="form form-horizontal">
      <input type="hidden" name="gatewayClassname" value="{$gateway|get_class|escape}" />
      <input type="hidden" name="action" value="save" />

      {foreach from=$form_fields key=field_id item=field_info}
        <div class="form-group">
          <label class="label-control col-sm-3">
            [[$field_info.caption]]
            {if $field_info.is_required}<i class="icon-asterisk smaller-60"></i>{/if}
          </label>
          <div class="col-sm-6">{input property=$field_id}</div>
        </div>
      {/foreach}
      <div class="clearfix form-actions">
        <input type="submit" value="[[Save:raw]]" class="btn btn-default">
      </div>
    </form>
  </div>
</div>
