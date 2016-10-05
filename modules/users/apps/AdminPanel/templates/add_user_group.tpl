<div class="breadcrumbs">
  <ul class="breadcrumb">
    <li><a href="{page_path id='user_groups'}">[[User Groups]]</a></li>
    <li>[[Add a New User Group]]</li>
  </ul>
</div>
<div class="page-content">
  <div class="page-header">
    <h1>[[Add User Group]]</h1>
  </div>

  <div class="row">
    {display_error_messages}

    <div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60 "></i>) are mandatory]]</div>

    <form method="post" class="form form-horizontal">
    <input type="hidden" name="action" value="add">

      {foreach from=$form_fields key=field_id item=form_field}
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
