<select name='{$id}[equal]' onchange="reloadWithParameter(this.value)" class="form-control">
	<option value="">Any {$caption}</option>
	{foreach from=$list_values item=list_value}
		<option value='{$list_value.id|escape}' {if $selected_category_id === $list_value.id}selected{/if} >{$list_value.caption}</option>
	{/foreach}
</select>

{literal}
<script type="tetext/javascript">
function reloadWithParameter(param)
{
	window.location = "?category_id="+param;
}
</script>
{/literal}
