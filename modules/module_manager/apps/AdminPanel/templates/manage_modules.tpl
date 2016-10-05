<div class="breadcrumbs">
  <ul class="breadcrumb">
    <li>[[Module Manager]]</li>
  </ul>
</div>
<div class="page-content">
  <div class="page-header">
	  <h1>[[Module Manager]]</h1>
  </div>

  <div class="row">
    {display_error_messages}
    {display_warning_messages}
    {display_success_messages}

    {capture name=refreshModuleListButton}
      <div class="space-8"></div>
      <form method="post">
        {CSRF_token}
        <input type="hidden" name="action" value="refresh_module_list">
        <input type="submit" value="[[Refresh Module List:raw]]" class="btn btn-default">
      </form>
      <div class="space-8"></div>
    {/capture}

    <div class="col-xs-12 ModuleManagerBlock">
      <div class="row">
        <div class="col-sm-12 usersBlock">
          <div class="tabbable">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#Available" data-toggle="tab">[[Available Modules]]</a></li>
              <li><a href="#SampleData" data-toggle="tab">[[Sample Data Modules]]</a></li>
              <li><a href="#System" data-toggle="tab">[[System Modules]]</a></li>
              <li><a href="#ModuleUpdates" data-toggle="tab">[[Module Updates]] ({$moduleUpdates|count})</a></li>
              <li><a href="#Addon" data-toggle="tab">[[Addon Modules]] ({$addonModules|count})</a></li>
            </ul>
            <div class="tab-content">
              <div id="Available" class="tab-pane active">
                {$smarty.capture.refreshModuleListButton}
                <div class="dataTables_wrapper" role="grid">
                  <form method="post" name="itemSelectorForm">
                  {CSRF_token}
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="btn-group">
                        <button class="btn btn-primary dropdown-toggle caption" data-toggle="dropdown">
                          [[Mass Actions]]
                          <i class="icon-angle-down icon-on-right"></i>
                        </button>
                        <ul class="dropdown-menu">
                          <li><a onclick="return submitItemSelectorForm(this, false)" href="?action=enable">[[Enable Selected]]</a></li>
                          <li><a onclick="return submitItemSelectorForm(this, false)" href="?action=disable">[[Disable Selected]]</a></li>
                          <li><a onclick="return submitItemSelectorForm(this, false)" href="?action=upgrade">[[Upgrade Selected]]</a></li>
                          <li><a onclick="return submitItemSelectorForm(this, false)" href="?action=install">[[Install Selected]]</a></li>
													<li><a class="checkAll" href="#">Check all</a></li>
													<li><a class="uncheckAll" href="#">Uncheck all</a></li>
                        </ul>
                      </div>
                    </div>
                  </div>

                    <table class="table table-hover table-striped">
                      <thead>
                        <tr>
                          <th class="center">
                            <label>
                              <input class="ace checkAll" type="checkbox"/>
                              <span class="lbl"></span>
                            </label>
                          </th>
                          <th>[[Caption]]</th>
                          <th>[[Version]]</th>
                          <th>[[Status]]</th>
                          <th>[[Actions]]</th>
                        </tr>
                      </thead>
                      <tbody>
                      {foreach from=$availableModules item=module}
                        <tr class="{$module.status}">
                          <td class="center">
                            <label>
                              <input class="ace" type="checkbox" name="modules[]" value="{$module.name}" {if $module.checked} checked="checked"{/if} />
                              <span class="lbl"></span>
                            </label>
                          </td>
                          <td>{$module.caption}</td>
                          <td class="center">v.{$module.version}</td>
                          <td class="fieldValue status">
                            {if $module.status == 'ENABLED'}
                              [[Enabled]]
                            {elseif $module.status == 'DISABLED'}
                              [[Disabled]]
                            {elseif $module.status == 'READY_FOR_INSTALLATION'}
                              [[Ready for installation]]
                            {elseif $module.status == 'NEEDS_UPGRADE'}
                              [[Needs upgrade]]
                            {elseif $module.status == 'NO_LICENSE'}
                              [[No license]]
                            {/if}
                          </td>
                          <td class="center">
                            {if $module.status == 'ENABLED'}
                              <a class="itemControls disable" href="?action=disable&modules[]={$module.name}" title="[[Disable:raw]]">[[Disable]]</a>
                            {elseif $module.status == 'DISABLED'}
                              <a class="itemControls enable" href="?action=enable&modules[]={$module.name}" title="[[Enable:raw]]">[[Enable]]</a>
                            {elseif $module.status == 'READY_FOR_INSTALLATION'}
                              <a class="itemControls install" href="?action=install&modules[]={$module.name}" title="[[Install:raw]]">[[Install]]</a>
                            {elseif $module.status == 'NEEDS_UPGRADE'}
                              <a class="itemControls upgrade" href="?action=upgrade&modules[]={$module.name}" title="[[Upgrade:raw]]">[[Upgrade]]</a>
                            {/if}
                          </td>
                        </tr>
                        {foreachelse}
                        <tr>
                          <td colspan="5">[[No modules found]]</td>
                        </tr>
                      {/foreach}
                      </tbody>
                    </table>
                  </form>
                </div>
              </div>

              <div id="SampleData" class="tab-pane">
                {$smarty.capture.refreshModuleListButton}
                <div class="dataTables_wrapper" role="grid">
                  <form method="post" name="itemSelectorForm">
                    {CSRF_token}
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="btn-group">
                          <button class="btn btn-primary dropdown-toggle caption" data-toggle="dropdown">
                            [[Mass Actions]]
                            <i class="icon-angle-down icon-on-right"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li><a onclick="return submitItemSelectorForm(this, false)" href="?action=install">[[Install Selected]]</a></li>
														<li><a class="checkAll" href="#">Check all</a></li>
														<li><a class="uncheckAll" href="#">Uncheck all</a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <table class="table table-hover table-striped">
                      <thead>
                        <tr>
                          <th class="center">
                            <label>
                              <input class="ace" type="checkbox"/>
                              <span class="lbl"></span>
                            </label>
                          </th>
                          <th>[[Caption]]</th>
                          <th>[[Version]]</th>
                          <th>[[Status]]</th>
                          <th>[[Actions]]</th>
                        </tr>
                      </thead>
                      <tbody>
                      {foreach from=$sampleDataModules item=module}
                        <tr>
                          <td class="center">
                            <input class="ace" type="checkbox" name="modules[]" value="{$module.name}" {if $module.checked} checked="checked"{/if} />
                            <span class="lbl"></span>
                          </td>
                          <td>{$module.caption}</td>
                          <td>v.{$module.version}</td>
                          <td class="fieldValue status {$module.status}">
                            {if $module.status == 'ENABLED'}
                              [[Enabled]]
                            {elseif $module.status == 'READY_FOR_INSTALLATION'}
                              [[Ready for installation]]
                            {elseif $module.status == 'NO_LICENSE'}
                              [[No license]]
                            {/if}
                          </td>
                          <td>
                            {if $module.status == 'READY_FOR_INSTALLATION'}
                              <a class="itemControls install" href="?action=install&modules[]={$module.name}" title="[[Install:raw]]">[[Install]]</a>
                            {/if}
                          </td>
                        </tr>
                        {foreachelse}
                        <tr>
                          <td colspan="5">[[No modules found]]</td>
                        </tr>
                      {/foreach}
                      </tbody>
                    </table>
                  </form>
                </div>
              </div>

              <div id="System" class="tab-pane">
                {$smarty.capture.refreshModuleListButton}
                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>[[Caption]]</th>
                      <th>[[Version]]</th>
                    </tr>
                  </thead>
                  <tbody>
                    {foreach from=$systemModules item=module}
                      <tr>
                        <td>{$module.caption}</td>
                        <td>v.{$module.version}</td>
                      </tr>
                    {foreachelse}
                      <tr>
                        <td colspan="2">[[No modules found]]</td>
                      </tr>
                    {/foreach}
                  </tbody>
                </table>
              </div>

              <div id="ModuleUpdates" class="tab-pane">
                {$smarty.capture.refreshModuleListButton}
                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>[[Caption]]</th>
                      <th>[[Installed Version]]</th>
                      <th>[[Available Version]]</th>
                      <th>[[Invalid Master Modules]]</th>
                      <th>[[Invalid Dependent Modules]]</th>
                    </tr>
                  </thead>
                  <tbody>
                    {foreach from=$moduleUpdates item=module}
                      <tr>
                        <td><a onclick="javascript:window.open(this.href, '_blank'); return false;" href="{$module.url}">{$module.caption}</a></td>
                        <td>v.{$module.installedVersion}</td>
                        <td>v.{$module.availableVersion}</td>
                        <td>
                          {if !empty($module.invalidMasterModules)}
                            <ul>
                              {foreach from=$module.invalidMasterModules item=invalidModule}
                                <li>{$invalidModule.masterCaption} ({$invalidModule.min_version} - {$invalidModule.max_version})</li>
                              {/foreach}
                            </ul>
                          {/if}
                        </td>
                        <td>
                          {if !empty($module.invalidDependentModules)}
                            <ul>
                              {foreach from=$module.invalidDependentModules item=invalidModule}
                                <li>{$invalidModule.caption} requires {$invalidModule.masterCaption} ({$invalidModule.min_version} - {$invalidModule.max_version})</li>
                              {/foreach}
                            </ul>
                          {/if}
                        </td>
                      </tr>
                    {foreachelse}
                      <tr>
                        <td colspan="5">[[No modules found]]</td>
                      </tr>
                    {/foreach}
                  </tbody>
                </table>
              </div>

              <div id="Addon" class="tab-pane">
                {$smarty.capture.refreshModuleListButton}
                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>[[Caption]]</th>
                      <th>[[Version]]</th>
                      <th>[[Invalid Master Modules]]</th>
                    </tr>
                  </thead>
                  <tbody>
                    {foreach from=$addonModules item=module}
                      <tr>
                        <td><a onclick="javascript:window.open(this.href, '_blank'); return false;" href="{$module.url}">{$module.caption}</a></td>
                        <td>v.{$module.version}</td>
                        <td>
                          {if !empty($module.invalidMasterModules)}
                            <ul>
                              {foreach from=$module.invalidMasterModules item=invalidModule}
                                <li>{$invalidModule.masterCaption} ({$invalidModule.min_version} - {$invalidModule.max_version})</li>
                              {/foreach}
                            </ul>
                          {/if}
                        </td>
                      </tr>
                    {foreachelse}
                      <tr>
                        <td colspan="3">[[No modules found]]</td>
                      </tr>
                    {/foreach}
                  </tbody>
                </table>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
{require component="jquery" file="jquery.js"}
{require component="jquery-cookie" file="jquery.cookie.js"}
{require component="jquery-ui" file="jquery-ui.js"}
{require component="jquery-ui" file="css/smoothness/jquery-ui.css"}
<script type="text/javascript">
	var restoreRequest = "{isset($REQUEST.restore)}";
	$(document).ready(function() {
		$('.modulesTabs > ul > li > a[href^="#"]').click(function () {
			$(".modulesTabs > ul > li > a").removeClass("active");
			$(".modulesTabs > ul > li > a").parent().removeClass("active");
			$(".modulesTabs > div").hide();
			$(this.hash).show();
			$("a[href=" + this.hash + "]").addClass("active");
			$("a[href=" + this.hash + "]").parent().addClass("active");
			$.cookie("tabToClick_ModuleManager", this.hash);
			return false;
		});

		var cookie = $.cookie("tabToClick_ModuleManager");
		if (window.location.hash != '') {
			$(".modulesTabs > ul > li > a[href=" + window.location.hash + "]").click();
		}
		else if (restoreRequest == '1' && cookie) {
			$(".modulesTabs > ul > li > a[href=" + cookie + "]").click();
		}
		else {
			$('.modulesTabs > ul > li > a[href^="#"]').first().click();
		}

		$('table th input:checkbox').on('change', function () {
			var that = this;

			$(this).closest('table').find('tr > td:first-child input:checkbox')
					.each(function () {
						this.checked = that.checked;
						$(this).closest('tr').toggleClass('selected');
					});
		});

		var checkboxChecker = function(state){
			$('.tab-pane.active table').find('tr > td:first-child input:checkbox')
					.each(function () {
						$(this).prop('checked', state);
						$(this).closest('tr').toggleClass('selected');
					});
		}

		$('a.checkAll').click(function(){
			checkboxChecker(true);
		});
		$('a.uncheckAll').click(function(){
			checkboxChecker(false);
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

{include file="miscellaneous^multilevelmenu_js.tpl"}
</div>
