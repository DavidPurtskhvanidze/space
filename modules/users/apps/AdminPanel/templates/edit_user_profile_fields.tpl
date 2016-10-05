<div class="breadcrumbs">
  <ul class="breadcrumb">
	<li><a href="{page_path id='user_groups'}">[[User Groups]]</a></li>
	{if $user_group_sid != 0}<li><a href="{page_path id='edit_user_group'}?sid={$user_group_sid}">{$user_group_info.id}</a></li>{/if}
	<li>[[Edit User Profile Fields]]</li>
  </ul>
</div>

<div class="page-content">
  <div class="page-header">
    <h1>{if $user_group_sid eq 0}[[Common Profile Fields (appear in all groups)]]{else}[[Profile Fields For the User Group]] "[[PhrasesInTemplates!{$user_group_info.name}]]"{/if}</h1>
  </div>

  <div class="row">
    {display_error_messages}
    {if $showForm}
      <a href="{page_path id='add_user_profile_field'}?user_group_sid={$user_group_sid}">[[Add User Profile Field]]</a>
      <div class="space-8"></div>
      <table class="items sortable ui-sortable table table-striped" data-parent-value="{$user_group_sid}" data-sorting-url="{page_path id='edit_user_profile'}">
        <thead>
        <tr class="head">
          <th>[[SID]]</th>
          <th>[[ID]]</th>
          <th>[[Caption]]</th>
          <th>[[Type]]</th>
          <th>[[Required]]</th>
          <th colspan="2">[[Actions]]</th>
        </tr>
        </thead>
        {assign var="specificFields" value=false}
        <tbody>
          {foreach from=$user_profile_fields item=user_profile_field name=fields_block}
            {$isGroupField = $user_group_sid == $user_profile_field.user_group_sid}
            <tr class="{cycle values="odd,even"}"{if $isGroupField} data-item-sid="{$user_profile_field.sid}"{else} data-sorting-exclude="true"{/if}>
              <td>{$user_profile_field.sid}</td>
              <td>{$user_profile_field.id}</td>
              <td>[[FormFieldCaptions!{$user_profile_field.caption}]]</td>
              <td>[[$user_profile_field.type]]</td>
              <td>{if $user_profile_field.is_required.isTrue}[[Yes]]{else}[[No]]{/if}</td>
              {if $isGroupField}
                <td>
                  <a class="itemControls edit btn btn-xs btn-info" href="{page_path id='edit_user_profile_field'}?sid={$user_profile_field.sid}&user_group_sid={$user_group_sid}" title="[[Edit:raw]]">
                    <i class="icon-edit bigger-120"></i>
                  </a>
                  <a class="itemControls delete btn btn-xs btn-delete" href="{page_path id='delete_user_profile_field'}?sid={$user_profile_field.sid}&user_group_sid={$user_group_sid}" onclick="return confirm('[[Are you sure you want to delete this field?:raw]]')" title="[[Delete:raw]]">
                    <i class="icon-remove bigger-120"></i>
                  </a>
                </td>
                {assign var="specificFields" value=true}
                <td class="sort">
                  <span title="[[Drag and drop to change the order:raw]]">
                    <i class="icon-sort"></i>
                  </span>
                </td>
              {else}
                <td colspan="4">
                  <a href="{page_path id='edit_user_profile'}?user_group_sid={$user_profile_field.user_group_sid}">[[Common field]]</a>
                </td>
              {/if}
            </tr>
          {/foreach}
        </tbody>
      </table>
    {/if}
    {include file="miscellaneous^sortable_js.tpl"}
  </div>
</div>
