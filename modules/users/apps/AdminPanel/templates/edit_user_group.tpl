<div class="breadcrumbs">
  <ul class="breadcrumb">
    <li><a href="{page_path id='user_groups'}">[[User Groups]]</a></li>
    <li>{$user_group_info.id}</li>
  </ul>
</div>

<div class="page-content">
  <div class="page-header">
    <h1>[[Edit User Group]]</h1>
  </div>

  <div class="row">
    {display_error_messages}

    <form method="post" class="form form-horizontal">
    <input type="hidden" name="action" value="save_info">
    <input type="hidden" name="sid" value="{$object_sid}">
      {foreach from=$form_fields key=field_id item=form_field}
        <div class="form-group">
          <label class="control-label col-sm-3">
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

    <a class="btn btn-link" href="{page_path id='edit_user_profile'}?user_group_sid={$user_group_sid}">[[Edit User Profile Fields]]</a>

    <h3 class="header">[[Membership Plans]]</h3>
    <div class="row">
      <div class="col-xs-8 usersBlock">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>[[Name]]</th>
              <th>[[Type]]</th>
              <th>[[Number of Users]]</th>
              <th>[[Actions]]</th>
            </tr>
          </thead>
          <tbody>
            {foreach from=$user_group_membership_plans_info item=membership_plan_info}
              <tr>
                <td>[[$membership_plan_info.name]]</td>
                <td>[[$membership_plan_info.type]]</td>
                {assign var="membership_plan_sid" value=$membership_plan_info.id}
                <td>{$user_group_membership_plan_user_number.$membership_plan_sid}</td>
                <td>
                  {if $user_group_membership_plan_user_number.$membership_plan_sid == 0}
                    <a class="btn btn-xs btn-danger" href="?action=delete_membership_plan&sid={$user_group_sid}&membership_plan_sid={$membership_plan_info.sid}" onclick="return confirm('[[Are you sure you want to delete this membership plan?:raw]]')" title="[[Delete:raw]]">
                        <i class="icon-trash bigger-120"></i>
                    </a>
                  {/if}
                </td>
              </tr>
            {/foreach}
          </tbody>
        </table>
      </div>
    </div>

    <div class="space-10"></div>
    <h3 class="header">[[Add Membership Plan]]</h3>
    <div class="row">
      <div class="col-xs-8">
        <form method="post" class="form form-horizontal">
          {CSRF_token}
          <input type="hidden" name="action" value="add_membership_plan">
          <input type="hidden" name="sid" value="{$user_group_sid}">
          <div class="form-group">
            <select name="membership_plan_sid" class="form-control">
              <option value="0">[[Select Membership Plan]]</option>
              {foreach from=$membership_plans_info item=membership_plan_info}
                <option value="{$membership_plan_info.sid}">[[$membership_plan_info.name:raw]]</option>
              {/foreach}
            </select>
          </div>
          <div class="clearfix form-actions">
            <input type="submit" value="[[Add:raw]]" class="btn btn-default">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
