{if $parameters.id_prefix}
	{$id_attribute = " id=\"{$parameters.id_prefix}_{$id}\""}
{else}
	{$id_attribute = " id=\"input_{$id}\""}
{/if}
<select name="{$id}" {$id_attribute}
        class="list form-control {if $hasError}has-error{/if}"
        {if $hasError}data-error="{$error}"{/if}>
{section name=foo start=$minimum loop=$maximum+1}
    <option value="{$smarty.section.foo.index|escape}" {if $value eq $smarty.section.foo.index}selected="selected"{/if}>{$smarty.section.foo.index}</option>
{/section}
</select>
