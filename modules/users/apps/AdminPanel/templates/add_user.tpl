<div class="breadcrumbs">
  <ul class="breadcrumb">
    <li><a href="{page_path id='users'}?action=restore">[[Users]]</a></li>
    <li><a href="{page_path module='users' function='add_user'}">[[Add a New User]]</a></li>
    <li>[[$user_group.name]]</li>
  </ul>
</div>
<div class="page-content">
  <div class="page-header">
    <h1>[[Add User]]</h1>
  </div>
  <div class="row">
    {display_error_messages}

    <div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60 "></i>) are mandatory]]</div>
    <form method="post" enctype="multipart/form-data" class="form form-horizontal">
      <input type="hidden" name="action" value="add">
      <input type="hidden" name="user_group_id" value="{$user_group.id}">

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
          <input type="submit" value="[[Add:raw]]" class="btn btn-default" />
        </div>
    </form>
  </div>
</div>

</script>
