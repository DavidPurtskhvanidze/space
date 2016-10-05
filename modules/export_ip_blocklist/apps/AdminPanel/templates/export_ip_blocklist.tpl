<div class="breadcrumbs">
  <ul class="breadcrumb">
    <li><a href="{page_path module='ip_blocklist' function='blocklist'}">[[IP Blocklist]]</a></li>
    <li>[[Export IP list]]</li>
  </ul>
</div>

<div class="page-content">
  <div class="page-header">
    <h1>[[Export IP list]]</h1>
  </div>
  <div class="row">
    {if $emptyIpRangeList}
      <p class="text-error">[[The IP list is empty. You cannot export an empty list.]]</p>
      <p><a class="btn btn-link" href="{page_path module='ip_blocklist' function='blocklist'}">[[Return back]]</a></p>
    {else}
      <h4>[[IP Range Properties To Export]]</h4>
      <form method="post">
        {CSRF_token}
        <input type="hidden" name="action" value="export">
        <div class="control-group">
          {foreach from=$properties item=property name=properties}
            <div class="checkbox">
              <label>
                {if $property.id == 'ip_range'}
                  <input type="hidden" name="export_properties[]" value="{$property.id}"/>
                  <input class="ace" type="checkbox" value="1" checked="checked" disabled="disabled"/>
                {else}
                  <input class="ace" type="checkbox" name="export_properties[]" value="{$property.id}" id="checkbox_{$smarty.foreach.properties.iteration}" />
                {/if}
                <span class="lbl">
                  [[FormFieldCaptions!{$property.caption}]]
                </span>
              </label>
            </div>
          {/foreach}
        </div>
        <div class="clearfix">
          <a href="#" onClick="check_all();return false;">[[Select all]]</a> | <a href="#" onClick="uncheck_all();return false;">[[Deselect all]]</a>
        </div>
        <div class="clearfix form-actions">
          <input type="submit" value="[[Export:raw]]" class="btn btn-default">
        </div>
      </form>
    {/if}
  </div>

  <script type="text/javascript">
    var total_count={$smarty.foreach.properties.total};
    {literal}
    function check_all() 	{ set_checkbox_to(true); }
    function uncheck_all() 	{ set_checkbox_to(false); }

    function set_checkbox_to(flag)
    {
      for (i = 1; i <= total_count; i++)
      {
        if (checkbox = document.getElementById('checkbox_' + i))
        {
          checkbox.checked = flag;
        }
      }
    }
    {/literal}
  </script>
</div>
