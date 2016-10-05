  <div class="page-header">
    <h1>[[Add a New IP / IP range]]</h1>
  </div>
  <div class="row">
    {display_error_messages}

    <div class="alert alert-warning">
      <p>IP, IP range format examples:</p>
      <ul>
        <li>192.168.1.1 - Single IP address 192.168.1.1</li>
        <li>192.168.1 - IP range from 192.168.1.0 to 192.168.1.255</li>
        <li>192.168.1.1/24 - IP range from 192.168.1.0 to 192.168.1.255</li>
        <li>192.168.1.1/255.255.255.0 - IP range from 192.168.1.0 to 192.168.1.255</li>
      </ul>
    </div>

    <div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60"></i>) are mandatory]]</div>

    <form method="post" enctype="multipart/form-data" action="" class="form form-horizontal">
      <input type="hidden" name="action" value="save_info">
      <input type="hidden" name="returnBackUri" value="{$returnBackUri}">
      {foreach from=$form_fields key=field_id item=form_field}
        <div class="form-group">
          <label class="control-label col-sm-3">
            [[$form_field.caption]]
            {if $form_field.is_required}<i class="icon-asterisk smaller-60"></i>{/if}
          </label>
          <div class="col-sm-6">{input property=$form_field.id}</div>
        </div>
      {/foreach}
      <div class="clearfix form-actions">
        <input type="submit" value="[[Save:raw]]" class="btn btn-default">
      </div>
    </form>
  </div>
