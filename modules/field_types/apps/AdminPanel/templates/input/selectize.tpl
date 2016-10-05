<select name='{$id}' id="selectize_{$id}" class="form-control {if $hasError}has-error tooltip-error{/if}"
        {if $hasError}data-rel="tooltip" data-placement="top" title="{$error}"{/if}>

	<option value="">[[Miscellaneous!Select:raw]] [[FormFieldCaptions!{$caption}:raw]]</option>

	{foreach from=$list_values item=list_value}
		<option value='{$list_value.id|escape}'
		        {if $list_value.id == $value}selected{/if} >{tr mode="raw" domain="Property_$id"}{$list_value.caption}{/tr}</option>
	{/foreach}

</select>
{require component="jquery" file="jquery.js"}
{require component="twitter-bootstrap" file="css/bootstrap.min.css"}
{require component="twitter-bootstrap" file="js/bootstrap.min.js"}

{require component="selectize" file="css/selectize.css"}
{require component="selectize" file="css/selectize.bootstrap3.css"}
{require component="selectize" file="js/standalone/selectize.min.js"}
<script type="text/javascript">
	$(document).ready(function () {
		$("#selectize_{$id}").selectize({
			create: false,
			sortField: 'text',
            'onInitialize': function()
            {
                $("#selectize_{$id}").closest('div').find('.selectize-control').addClass("selectize_{$id}");
            }
		})
	});
</script>
