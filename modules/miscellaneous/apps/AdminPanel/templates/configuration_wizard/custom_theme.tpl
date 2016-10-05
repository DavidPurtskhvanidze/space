{function name=themesSelectOptions level=0}
	{$indent=$level*3}
	{foreach $theme->getChildren() as $childTheme}
		<option value="{$childTheme->getId()}"{if $childTheme->getId() == $selected} selected="selected"{/if}>{$childTheme->getId()|indent:$indent:"&nbsp;"}</option>
		{if $childTheme->hasChildren()}
			{themesSelectOptions theme=$childTheme level=$level+1 selected=$selected}
		{/if}
	{/foreach}
{/function}

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
