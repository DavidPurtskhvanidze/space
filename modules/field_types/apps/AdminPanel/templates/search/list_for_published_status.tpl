<select name='{$id}[equal]' class="form-control">
	<option value="">[[Miscellaneous!Any]] [[Status]]</option>
	<option value='1' {if $value.equal == '1'}selected{/if} >[[Published Comments]]</option>
	<option value='0' {if $value.equal == '0'}selected{/if} >[[Hidden Comments]]</option>
</select>
