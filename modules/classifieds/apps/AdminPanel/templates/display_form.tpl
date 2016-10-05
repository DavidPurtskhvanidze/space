
<table class="basetable">

	{foreach from=$form_fields item=form_field}

		<tr class="{cycle values = 'evenrow,oddrow' advance=false}" onmouseover="this.className='highlightrow'" onmouseout="this.className='{cycle values = 'evenrow,oddrow'}'">
			<td>{$form_field.caption}</td>
			<td> {display property=$form_field.id}</td>
		</tr>

	{/foreach}

</table>
