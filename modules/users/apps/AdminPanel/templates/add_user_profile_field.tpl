<div class="breadcrumbs">
  <ul class="breadcrumb">
	  <li><a href="{page_path id='user_groups'}">[[User Groups]]</a></li>
    {if $user_group_sid != 0}<li><a href="{page_path id='edit_user_group'}?sid={$user_group_sid}">{$user_group_info.id}</a></li>{/if}
    <li><a href="{page_path id='edit_user_profile'}?user_group_sid={$user_group_sid}">[[Edit User Profile Fields]]</a></li>
    <li>[[Add Field]]</li>
  </ul>
</div>

<div class="page-content">
  <div class="page-header">
    <h1>[[Add User Profile Field]]</h1>
  </div>

{display_error_messages}

  <div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60 "></i>) are mandatory]]</div>
  <form method="post" class="form form-horizontal">
    <input type="hidden" name="action" value="add">
    <input type="hidden" name="user_group_sid" value="{$user_group_sid}">

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
