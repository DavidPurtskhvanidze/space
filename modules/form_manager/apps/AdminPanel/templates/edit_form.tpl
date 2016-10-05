<div class="searchForm editListValues">
  <div class="breadcrumbs">
    <ul class="breadcrumb">
      <li><a href="{page_path module='form_manager' function='manage_forms'}">[[Manage Forms]]</a></li>
      <li>[[Edit Form]]</li>
    </ul>
  </div>
  <div class="page-content">
    <div class="page-header">
      <h1>[[Edit Form {$form_info.id}]]</h1>
    </div>
    {display_error_messages}
    {display_success_messages}
    <div class="widget-box no-border">
      <div class="widget-header header-color-dark">
        <h4 class="white">
          <a href="#" data-action="collapse" title="collapse"><i class="icon-chevron-up"></i> [[Edit details]]</a>
        </h4>
      </div>
      <div class="widget-body">
        <form class="form form-horizontal" method="post" enctype="multipart/form-data" role="form">
          {CSRF_token}
          <div class="clearfix form-actions">
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">[[Title]]</label>
              <div class="col-sm-8"><input type="text" name="title" class="form-control" value="{$form_info.title}"></div>
            </div>
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="sid" value="{$form_info.sid}">
            <input type="hidden" name="application_id" value="{$application_id}">
            <input type="submit" value="[[Save:raw]]" class="btn btn-default">
          </div>
        </form>
      </div>
    </div>
    <div class="widget-box no-border">
      <div class="widget-header header-color-dark">
        <h4 class="white">
          <a href="#" data-action="collapse" title="collapse"><i class="icon-chevron-up"></i> [[Edit fields]]</a>
        </h4>
      </div>
    <div class="widget-body">
      <form class="form form-horizontal" method="post" enctype="multipart/form-data" role="form">
        {CSRF_token}
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="application_id" value="{$application_id}">
        <input type="hidden" name="sid" value="{$form_info.sid}">
        <div class="clearfix form-actions">
          <div class="form-group">
            <label for="" class="col-sm-3 control-label">[[New Field]]</label>
            <div class="col-sm-8">
              <select class="form-control" name="field_id">
                <option value="">[[Chose Any]]</option>
                <option value="Fieldset">[[Fieldset]]</option>
                {if $category_fields|count > 0}<option disabled>──────────</option>{/if}
                {foreach from=$category_fields item=field}
                  <option value="{$field}">{$field}</option>
                {/foreach}
              </select>
            </div>
          </div>
          <div class="form-group extra-info" style="display:none">
            <label for="" class="col-sm-3 control-label">[[Title]]</label>
            <div class="col-sm-8">
              <input type="text" name="title" class="form-control" value="{$form_id}">
            </div>
          </div>
          <input type="submit" value="[[Add:raw]]" class="btn btn-default" />
        </div>
      </form>

      {if $fields_info|count > 0}
        <form method="post" action="" name="itemSelectorForm">
        {CSRF_token}
        <table class="items sortable table table-striped table-hover" data-sorting-url="{page_path module='form_manager' function='edit_form'}?sid={$form_info.sid}">
          <tr class="head">
            <th>[[Fields]]</th>
            <th class="hidden-sm hidden-xs">[[Caption]]</th>
            <th colspan="2">[[Actions]]</th>
          </tr>
          {foreach from=$fields_info item=field}
            <tr class="editableContainer {cycle values="odd,even"}" data-item-sid="{$field.sid}" id="{$field.field_id}">
              <td>
                <div class="editableCaption">[[$field.field_id]]</div>
              </td>
              <td><div class="editableCaption">[[$field.caption]]</div></td>
              <td>
                <a class="itemControls delete btn btn-xs btn-danger" href="?sid={$form_info.sid}&action=delete&field_sid={$field.sid}&application_id={$application_id}" onclick='return confirm("[[Are you sure that you want to delete this field?:raw]]")' title="[[Delete]]" data-rel="tooltip" data-original-title="[[Delete]]"><i class="icon-trash bigger-120"></i></a>
              </td>
              <td class="sort">
                <span title="[[Drag and drop to change the order:raw]]">
                  <i class="icon-sort"></i>
                </span>
              </td>
            </tr>
          {/foreach}
        </table>
        </form>
      {/if}
      </div>
    </div>
    {require component="jquery" file="jquery.js"}
    {require component="jquery-ui" file="jquery-ui.js"}
    {require component="jquery-ui" file="css/smoothness/jquery-ui.css"}
  </div>
</div>
{include file="miscellaneous^sortable_js.tpl"}
<script>
  $('select[name="field_id"]').on('change',function(){
    switch($(this).find('option:selected').val())
    {
      case 'Fieldset':
        $('div.extra-info').show();
        break;
      default:
        $('div.extra-info').hide();
    }
  });

</script>
