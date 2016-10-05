<div class="breadcrumbs">
  <ul class="breadcrumb">
	  <li>[[Page Templates]]</li>
  </ul>
</div>

<div class="page-content">
  <div class="page-header">
    <h1>[[Page Templates]]</h1>
  </div>

  <div class="row">
    {include file="errors.tpl"}

    {if $moduleIsEditable}
      <div class="alert alert-warning">
        [[You cannot delete templates which are inherited from the parent theme.]]
      </div>
      {assign var="isWritable" value=true}
      <h4>[[Create Template]]</h4>
      <form class="form-inline" role="form">
        <input type="hidden" name="action" value="create">
        <input type="hidden" name="application_id" value="{$appId}">
        <div class="form-group">
          <label class="sr-only">[[New Template Name]]</label>
          <input type="text" name="template" value="{$template}" class="form-control" placeholder="[[New Template Name:raw]]">
        </div>
        <input type="submit" value="[[Create:raw]]" class="btn btn-default">
      </form>
    {/if}

    <div class="space-8"></div>

    <table class="table table-striped table-hover">
      <thead>
      <tr>
        <th>[[Template]]</th>
        <th>[[Actions]]</th>
      </tr>
      </thead>
      <tbody>
        {foreach from=$templatesList key=templateName item=inherited}
          <tr class="{cycle values="odd,even"}">
            <td>{$templateName}</td>
            <td>
              <div class="btn-group">
                {if $moduleIsEditable}
                  <a class="itemControls edit btn btn-xs btn-info" href="?application_id={$appId}&amp;action=edit&amp;template={$templateName}" title="[[Edit:raw]]">
                    <i class="icon-edit"></i>
                  </a>
                {else}
                  <a class="itemControls view btn btn-xs btn-success" href="?application_id={$appId}&amp;action=view&amp;template={$templateName}" title="[[View:raw]]">
                    <i class="icon-eye-open"></i>
                  </a>
                {/if}
              </div>
              {if $moduleIsEditable && !$inherited}
                <a class="delete btn btn-xs btn-danger" href="?application_id={$appId}&amp;action=delete&amp;template={$templateName}" title="[[Delete:raw]]" onclick="return confirm('[[Are you sure that you want to delete this template?:raw]]')">
                  <i class="icon-trash"></i>
                </a>
              {/if}
            </td>
          </tr>
        {foreachelse}
          <tr><td colspan="3">[[No templates]]</td></tr>
        {/foreach}
      </tbody>
    </table>
  </div>
</div>
