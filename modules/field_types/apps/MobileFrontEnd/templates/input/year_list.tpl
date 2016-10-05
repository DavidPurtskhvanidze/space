{if $hasError}<div class="error validation">{$error}</div>{/if}
<select id="{$id}" name="{$id}" class="list">
{section name=foo start=$minimum loop=$maximum+1}
    <option value="{$smarty.section.foo.index}" {if $value eq $smarty.section.foo.index}selected{/if}>{$smarty.section.foo.index}</option>
{/section}
</select>
