<div class="sitePages filterForm">
  <div class="breadcrumbs">
    <ul class="breadcrumb">
      [[Site Pages]]
    </ul>
  </div>

  <div class="page-content">
    <div class="page-header">
      <h1 class="lighter">[[Site Pages]]</h1>
    </div>

    <div class="row">
      <a class="btn btn-link" href="{page_path module='site_pages' function='add_site_page'}?application_id={$applicationId}">
        [[Add a New Site Page]]
      </a>

      {display_error_messages}
      {display_success_messages}

      <div class="col-sm-12 usersBlock">
        <div class="widget-box">
          <div class="widget-header widget-header-small header-color-dark">
            <h4 title="[[Click to hide the filter form:raw]]">
							<a data-action="collapse" href="#">
									<i class="bigger-125 icon-chevron-down"></i> [[Column Filter]]
								</a>
            </h4>
          </div>
          <div class="widget-body">
            <div class="widget-main">
              <form class="from from-horizontal" name="filter_form" id="FilterForm">
                <div class="from-group">
                  <div class="row">
                  <div class="checkbox-group">
                    {foreach $optionalColumnList as $optionalColumn}
                      <label>
                        <input class="ace" type="checkbox" name="selectedColumnsList[]" value="{$optionalColumn.id}"
                               {if $optionalColumn.visible}checked="checked" {/if}>
                        <span class="lbl"> [[{$optionalColumn.caption}]]</span>
                      </label>
                    {/foreach}
                  </div>
                  </div>
                </div>
                <div class="clearfix from-actions">
                  <input type="submit" name="filter" value="[[Filter:raw]]" class="btn btn-xs btn-primary"/>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      {if $REQUEST.sortingOrder=='ASC'}
        {assign var="sortedColumnHrefParam" value="DESC"}
      {elseif $REQUEST.sortingOrder=='DESC'}
        {assign var="sortedColumnHrefParam" value="ASC"}
      {/if}

      <div class="col-xs-12">
        <div class="table-responsive">
          <div class="dataTables_wrapper" role="grid">
            <form>
              <div class="row">
                <div class="col-sm-6">
                  <a href="#" class="btn btn-xs dropdown-toggle btn-primary actionWithSelected" data-toggle="dropdown">
                    [[Mass Actions]]
                    <i class="icon-angle-down icon-on-right"></i>
                  </a>
                  <ul class="dropdown-menu actionList">
                    {foreach from=$sitePageMassActions item='action'}
                    {* show only actions developed to the current application *}
                      {if in_array($applicationId, $action->getApplicationIds())}
                        <li><a onclick="return submitItemSelectorForm(this, false)"
                               href="?action={$action->getId()}">[[{$action->getCaption()}]]</a></li>
                      {/if}
                    {/foreach}
                  </ul>
                </div>
              </div>
              {assign var="columnClass" value=""}
              <table class="table table-striped table-hover">
                <thead>
                  <tr class="head">
                    <th class="center">
                      <label>
                        <input class="ace" type="checkbox" />
                        <span class="lbl"></span>
                      </label>
                    </th>
                      <th>
                          {if $REQUEST.sortingField == 'id'}
                              {assign var='link' value='?sortingField=id&amp;sortingOrder='|cat:$sortedColumnHrefParam}
                              {if $REQUEST.sortingOrder == 'ASC'}
                                  {assign var='icon' value='icon-sort-up'}
                              {else}
                                  {assign var='icon' value='icon-caret-down'}
                              {/if}
                          {elseif $REQUEST.sortingField|is_null}
                              {assign var='link' value='?sortingField=id&amp;sortingOrder=DESC'}
                          {else}
                              {assign var='link' value='?sortingField=id&amp;sortingOrder=ASC'}
                          {/if}
                          <a href="{$link}"><i class="{$icon}"></i>[[Id]]</a>
                      </th>
                    <th>
                        {if $REQUEST.sortingField == 'uri'}
                            {assign var='link' value='?sortingField=uri&amp;sortingOrder='|cat:$sortedColumnHrefParam}
                        {elseif $REQUEST.sortingField|is_null}
                            {assign var='link' value='?sortingField=uri&amp;sortingOrder=DESC'}
                        {else}
                            {assign var='link' value='?sortingField=uri&amp;sortingOrder=ASC'}
                        {/if}
                        {if ($REQUEST.sortingField == 'uri') || ($REQUEST.sortingField|is_null)}
                            {if $REQUEST.sortingOrder == 'ASC'}
                                {assign var='uriIcon' value='icon-sort-up'}
                            {else}
                                {assign var='uriIcon' value='icon-caret-down'}
                            {/if}
                        {/if}
                        <a href="{$link}"><i class="{$uriIcon}"></i>[[URI]]</a>
                    </th>
                    {foreach $optionalColumnList as $optionalColumn}

                      {if $optionalColumn.visible}
                        <th class="{if in_array($optionalColumn.id, array('function', 'module', 'template'))}hidden-xs{/if}">
                          <a
                              {if $REQUEST.sortingField == $optionalColumn.id}
                            href="?sortingField={$optionalColumn.id}&amp;sortingOrder={$sortedColumnHrefParam}"
                            class="columnSorted {$REQUEST.sortingOrder|strtolower}"
                              {else} href="?sortingField={$optionalColumn.id}&amp;sortingOrder=ASC"
                              {/if}>[[{$optionalColumn.caption}]]
                          </a>
                        </th>
                      {/if}
                    {/foreach}
                    <th class="col-sm-2">[[Actions]]</th>
                  </tr>
                </thead>
                <tbody>
                  {foreach from=$pages_list item=page name="foreach"}
                    <tr>
                      <td class="center">
                        <label>
                          <input class="ace" type="checkbox" name="site_pages[]" value="{$page.uri}" {if $page.checked}
                            checked="checked"{/if} />
                          <span class="lbl"></span>
                        </label>
                      </td>
                      <td>{$page.id}</td>
                      <td>{$page.uri}</td>
                      {foreach $optionalColumnList as $optionalColumn}
                        {if $optionalColumn.visible}
                          <td class="{if in_array($optionalColumn.id, array('function', 'module', 'template'))}hidden-xs{/if}">{$page[$optionalColumn.id]}</td>
                        {/if}
                      {/foreach}
                      <td>
                        <div class="btn-group">
                          {if $viewActionDisplay}
                              <a class="btn btn-xs btn-success itemControls" href="{$appSiteUrl}{$page.uri}" target="_blank" title="[[View:raw]]">
                                <i class="icon-external-link bigger-110"></i>
                              </a>
                          {/if}
                          <a class="btn btn-xs btn-info itemControls edit" href="{page_path module='site_pages' function='edit_site_page'}?application_id={$applicationId}&id={$page.id}" title="[[Edit:raw]]">
                            <i class="icon-edit bigger-110"></i>
                          </a>
                          <a class="btn btn-xs btn-danger itemControls delete" href="?action=delete&site_pages[]={$page.uri}" onclick="return confirm('[[Are you sure you want to delete this page?:raw]]')" title="[[Delete:raw]]">
                            <i class="icon-trash bigger-110"></i>
                          </a>
                        </div>
                      </td>
                    </tr>
                  {/foreach}
                </tbody>
              </table>
              <div class="row">
              </div>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
{require component="jquery" file="jquery.js"}
{require component="jquery-ui" file="jquery-ui.js"}
{require component="jquery-cookie" file="jquery.cookie.js"}
<script type="text/javascript">
    $(document).ready(function () {

      $('table th input:checkbox').on('click' , function(){
        var that = this;
        $(this).closest('table').find('tr > td:first-child input:checkbox')
          .each(function(){
            this.checked = that.checked;
            $(this).closest('tr').toggleClass('selected');
          });
      });
    });

    function submitItemSelectorForm(anchor, confirmationMessage) {
        if (confirmationMessage && !confirm(confirmationMessage)) {
            return false;
        }
        window.location.href = $(anchor).attr("href") + "&" + $(anchor).parents('form').serialize();
        return false;
    }


</script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.mySlideContent').click(function(){
			$('.widget-body').slideToggle('slow');
			return false;
		});
		$('.collaps2').click(function(){
			$('.widget-body').slideToggle('slow');
			return false;
		});
		$('.widget-box').filterState('sitePages');
	});
</script>
</div>
