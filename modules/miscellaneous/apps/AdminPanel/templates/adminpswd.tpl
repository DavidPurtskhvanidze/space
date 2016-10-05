<div class="breadcrumbs">
  <ul class="breadcrumb">
    <li>[[Admin Password]]</li>
  </ul>
</div>

<div class="page-content">
  <div class="page-header">
    <h1>[[Admin Password]]</h1>
  </div>

  <div class="row">
    <div class="alert alert-warning">
      [[We recommend that you change the default admin username and password following the installation.<br />Please safely store it somewhere, as resetting the lost admin username/password will require knowledge of MySQL.]]
    </div>

    <h4 class="headerBlue">[[Change Administrator's Username and Password]]</h4>

    {display_error_messages}

    {if $usernameAndPasswordChanged}<p class="text-success">[[Administrator username and password have been changed.]]</p>{/if}

    <form method="post" class="form form-horizontal">
      {CSRF_token}
      <input type="hidden" name="action" value="change_admin_account">
      {foreach from=$form_items key=item_name item=item_params}
        <div class="form-group">
          <label class="control-label col-sm-3">[[{$item_params.caption}]]</label>
          <div class="col-sm-6">
          {if $item_params.type == 'static'}
            {$item_params.value}
          {else}
            <input type="{$item_params.type}" name="{$item_name}" value="{$item_params.value}" class="form-control">
          {/if}
          </div>
        </div>
      {/foreach}
      <div class="clearfix form-actions">
        <input type="submit" value="[[Change:raw]]" class="btn btn-default">
      </div>
    </form>
  </div>
</div>
