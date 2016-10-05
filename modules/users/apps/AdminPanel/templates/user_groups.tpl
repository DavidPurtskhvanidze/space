<div class="breadcrumbs">
  <ul class="breadcrumb">
    <li>[[User Groups]]</li>
  </ul>
</div>

<div class="page-content user-groups">
    <div class="page-header">
      <h1 class="lighter">[[User Groups]]</h1>
    </div>

  <div class="row">
    <ul class="list-inline">
      <li><a class="btn btn-link" href="{page_path id='edit_user_profile'}?user_group_sid=0">[[Edit Common User Profile Fields]]</a></li>
      <li><a class="btn btn-link" href="{page_path id='add_user_group'}">[[Add a New User Group]]</a></li>
    </ul>
  </div>
    <br />

  {display_error_messages}

  <table class="table table-striped">
    <thead>
      <tr class="head">
        <th>[[ID]]</th>
        <th>[[Name]]</th>
        <th class="col-md-5">[[Description]]</th>
        <th>[[Number of Users]]</th>
        <th>[[Status]]</th>
        <th>[[Actions]]</th>
      </tr>
    </thead>
    <tbody>
      {foreach from=$user_groups item=user_group}
        <tr data-item-sid="{$user_group.sid}">
          <td>{$user_group.id}</td>
          <td>[[$user_group.caption]]</td>
          <td>[[$user_group.description]]</td>
          <td>{$user_group.user_number}</td>
          <td>
            {if $user_group.active eq "1"}<span class="label label-sm arrowed-right label-success">[[Active]]</span>{else}<span class="label label-sm arrowed-right label-warning">[[Inactive]]</span>{/if}
          </td>
          <td>
            <div class="btn-group">
            <a class="itemControls edit btn btn-xs btn-info" href="{page_path id='edit_user_group'}?sid={$user_group.sid}" title="[[Edit:raw]]">
              <i class="icon-edit bigger-120"></i>
            </a>

            {if $user_group.user_number == 0}
              <a class="itemControls delete btn btn-xs btn-danger" href="{page_path id='delete_user_group'}?sid={$user_group.sid}" onclick="return confirm('[[Are you sure you want to delete this user group?:raw]]')" title="[[Delete:raw]]">
                <i class="icon-trash bigger-120"></i>
              </a>
            {/if}
            </div>
          </td>
        </tr>
      {/foreach}
    </tbody>
  </table>

  <div class="alert alert-info">[[You can delete only those User Groups which do not have registered users.]]</div>
</div>
