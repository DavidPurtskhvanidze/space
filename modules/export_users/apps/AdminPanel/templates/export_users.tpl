<div class="exportUsers">
	<div class="breadcrumbs">
    <ul class="breadcrumb">
      <li>[[Export Users]]</li>
    </ul>
  </div>

  <div class="page-content">
    <div class="page-header">
      <h1>[[Export Users]]</h1>

    </div>

    <div class="row">
      <div class="alert alert-warning">
        [[Please select a User Group below if you want to import both common and group specific user fields.]]
      </div>

        {display_error_messages}

        <form method="post" class="form form-horizontal">
          {CSRF_token}
          <input type="hidden" name="action" value="export">

          <h4 class="headerBlue">[[Export Filter]]</h4>
          
          <div class="form-group">
            <label class="col-sm-2 control-label">[[User Group]]</label>
            <div class="col-sm-6">
              <select name="user_group_sid" onchange="window.location = '?user_group_sid='+this.value" class="form-control">
                <option value="">[[Choose User Group]]</option>
                {foreach from=$user_groups item=user_group}
                  <option value="{$user_group.sid}"{if $user_group.sid == $user_group_sid} selected{/if}>[[$user_group.name:raw]]</option>
                {/foreach}
              </select>
            </div>
          </div>

          <h4 class="headerBlue">[[Users Properties To Export]]</h4>
          <div class="form-group row">
            <div class="control-group">
              {foreach from=$properties item=property name=properties}
                <div class="col-sm-3">
                  <div class="checkbox">
                    <label>
                      {if $property.id == 'username'}
                        <input class="ace ace-switch ace-switch-6" type="checkbox" value="1" checked="checked" disabled="disabled"/>
                        <span class="lbl"></span>
                      {else}
                          <input class="ace ace-switch ace-switch-6" type="checkbox" name="export_properties[]" value="{$property.id}" id="checkbox_{$smarty.foreach.properties.iteration}" />
                        <span class="lbl"></span>
                      {/if}
                      [[FormFieldCaptions!{$property.caption}]]
                    </label>
                  </div>
                </div>
              {/foreach}
            </div>
          </div>

          <div class="col-sm-12 selectAllBlock">
            <a href="#" onClick="check_all();">[[Select all]]</a> | <a href="#" onClick="uncheck_all();">[[Deselect all]]</a>
          </div>

          <div class="clearfix form-actions clearBothBlock">
            <input type="submit" value="[[Export:raw]]" class="btn btn-default">
          </div>

        </form>

        {require component="jquery" file="jquery.js"}
        <script type="text/javascript">
        {literal}
          function check_all()
          {
            $('input[name^=export_properties]').prop("checked", true);
            return false;
          }
          function uncheck_all() {
            $('input[name^=export_properties]').prop("checked", false);
            return false;
          }
        {/literal}
        </script>
    </div>
  </div>
</div>
