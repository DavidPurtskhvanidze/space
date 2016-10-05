{function name=themesSelectOptions level=0}
	{$indent=$level*3}
	{foreach $theme->getChildren() as $childTheme}
		<option value="{$childTheme->getId()}"{if $childTheme->getId() == $selected} selected="selected"{/if}>{$childTheme->getId()|indent:$indent:"&nbsp;"}</option>
		{if $childTheme->hasChildren()}
			{themesSelectOptions theme=$childTheme level=$level+1 selected=$selected}
		{/if}
	{/foreach}
{/function}

{function name=themesList level=0}
  {foreach $theme->getChildren() as $childTheme}

    {if $childTheme->hasChildren()}
      <div class="tree-folder" style="display:block;" data-item-id="{$childTheme->getId()}">
        <div class="tree-folder-header">
          <i class="icon-folder-open"></i>
          <div class="tree-folder-name{if $childTheme->getExtraParameter("is_current")} selected{/if}">
            {$childTheme->getId()}
          </div>
          <div class="actions">
            <div class="btns">
							{if !$childTheme->hasChildren() && !$childTheme->getExtraParameter("read_only") && !$childTheme->getExtraParameter("is_current")}
								<a class="itemControls delete btn btn-xs btn-danger" href="?application_id={$appId}&amp;action=delete_theme&amp;theme={$childTheme->getId()}"
									 onclick="return confirm('[[Are you sure you want to delete this theme?:raw]]')"
									 title="[[Delete:raw]]"><i class="icon-trash"></i></a>
							{/if}
              {if !$childTheme->getExtraParameter("read_only")}
                <a class="btn btn-xs btn-info" href="{page_path module='template_manager' function='edit_design_files'}?application_id={$appId}&amp;theme={$childTheme->getId()}"
                   title="[[Edit design.css]]"><i class="icon-edit"></i></a>
              {/if}
	          {if $childTheme->getExtraParameter("is_current")}
	            <a class="btn btn-xs btn-pink"
	               href="{page_path module='template_manager' function='edit_colors'}?application_id={$appId}&amp;theme={$childTheme->getId()}"
	               title="[[Edit colors]]">
		            <i class="icon-adjust"></i>
	            </a>
		      {/if}
            </div>
            <div class="item">
              {if $childTheme->getExtraParameter("is_current")}
                <span class="label label-success arrowed">
									[[Current]]
								</span>
              {else}
                <a href="?application_id={$appId}&amp;action=make_current&amp;theme={$childTheme->getId()}">[[Make current]]</a>
              {/if}
            </div>
          </div>
        </div>
        <div class="tree-folder-content" style="display:block;">
          {themesList theme=$childTheme level=$level+1}
        </div>
      </div>
    {else}
      <div class="tree-item" style="display:block;" data-item-id="{$childTheme->getId()}">
        <div class="tree-item-name">
					<i class="icon-file-text-alt"></i>
					<span data-theme-name="{$childTheme->getId()}" class="{if $childTheme->getExtraParameter("is_current")}selected{/if}">{$childTheme->getId()}</span>
				</div>
        <div class="actions">
          <div class="btns">
						{if !$childTheme->hasChildren() && !$childTheme->getExtraParameter("read_only") && !$childTheme->getExtraParameter("is_current")}
							<a class="itemControls delete btn btn-xs btn-danger" href="?application_id={$appId}&amp;action=delete_theme&amp;theme={$childTheme->getId()}"
								 onclick="return confirm('[[Are you sure you want to delete this theme?:raw]]')"
								 title="[[Delete:raw]]"><i class="icon-trash"></i></a>
						{/if}
            {if !$childTheme->getExtraParameter("read_only")}
              <a class="btn btn-xs btn-info" href="{page_path module='template_manager' function='edit_design_files'}?application_id={$appId}&amp;theme={$childTheme->getId()}"
                 title="[[Edit design.css]]"><i class="icon-edit"></i></a>
            {/if}
	        {if $childTheme->getExtraParameter("is_current")}
	          <a class="btn btn-xs btn-pink"
	             href="{page_path module='template_manager' function='edit_colors'}?application_id={$appId}&amp;theme={$childTheme->getId()}"
	             title="[[Edit colors]]">
		          <i class="icon-adjust"></i>
	          </a>
		    {/if}
          </div>
          <div class="item">
            {if $childTheme->getExtraParameter("is_current")}
              <span class="label label-success arrowed">
								[[Current]]
							</span>
            {else}
              <a href="?application_id={$appId}&amp;action=make_current&amp;theme={$childTheme->getId()}">[[Make current]]</a>
            {/if}
          </div>
        </div>
      </div>
    {/if}

  {/foreach}
{/function}

<div class="themeEditor">
	<div class="breadcrumbs">
    <ul class="breadcrumb">
      <li>[[Themes]]</li>
    </ul>
  </div>

  <div class="page-content">
    <div class="page-header">
      <h1 class="lighter">[[Themes]]</h1>
    </div>

    <div class="row">
      <h4 class="headerBlue">[[Create Theme]]</h4>

      {display_error_messages}

    {if $error_code == 999000} {* ERROR_THEME_DIR_CANNOT_WRITE *}
      <p class="text-warning">[[You do not have permissions to write to the /templates folder.]] [[Please check the permissions
        are 755.]]</p>
      {elseif $error_code == 999005} {* ERROR_THEME_CANNOT_DELETE_HAS_CHILD *}
      <p class="text-warning">[[This theme cannot be deleted because it is used as a parent theme for the following theme(s)]]:
        {foreach from=$inheritors item="inheritor" key="key"}{if $key != 0}, {/if}{$inheritor}{/foreach}
      </p>
      {elseif $error_code == 999006} {* ERROR_THEME_CANNOT_DELETE_READONLY *}
      <p class="text-warning">[[The theme cannot be deleted since it is read-only.]]</p>
      {elseif $error_code == 999008} {* ERROR_THEME_CANNOT_DELETE_CURRENT *}
      <p class="text-warning">[[The theme cannot be deleted since it is current.]]</p>
      {elseif $error_code == 999800} {* ERROR_SMARTY_CACHE_CANNOT_WRITE *}
      <p class="text-warning">[[Cannot write to smarty cache directory.]] [[Please check the permissions are 755.]]</p>
    {/if}


      <form class="form-inline">
        <input type="hidden" name="application_id" value="{$appId}" />
        <input type="hidden" name="action" value="new_theme" />
        <div class="form-group">
          <label for="newThemeName">[[New Theme Name]]</label>
          <input type="text" name="new_theme" value="{$REQUEST.new_theme}" class="form-control" id="newThemeName">
        </div>
        <div class="form-group">
          <label for="themeBasedOn">[[Based on]]</label>
          <select name="base_theme" class="form-control" id="themeBasedOn">
            <option value="">[[Please select]]</option>
            {themesSelectOptions theme=$rootTheme selected=$REQUEST.base_theme}
          </select>
        </div>
        <input type="submit" value="[[Create:raw]]" class="btn btn-default btn-sm btn-bottom">
      </form>

      <div class="space-8"></div>
      <div class="alert alert-warning">
        [[In order to see a newly selected theme on the front-end of your website, when you switched a current theme, please restart your browser (close all windows and then open a browser with your website again), or clear a session for your website in your browser (please refer to the browser documentation in order to learn how to do this).]]
      </div>

      <div class="col-sm-8 usersBlock">
        <div class="widget-box">
          <div class="widget-header header-color-blue2">
            <h4>[[Themes]]</h4>
          </div>
          <div class="widget-body">
            <div class="widget-main padding-8">
              <div class="tree">
                {themesList theme=$rootTheme}
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
{include file="miscellaneous^dialog_window.tpl"}
