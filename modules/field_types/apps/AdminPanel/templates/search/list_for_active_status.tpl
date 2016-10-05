<select name='{$id}[equal]' class="form-control">
	<option value="">[[Miscellaneous!Any]] [[Active Status]]</option>
	<option value='1' {if $value.equal == '1'}selected{/if} >[[Active listings]]</option>
	<option value='0' {if $value.equal == '0'}selected{/if} >[[Inactive listings]]</option>
</select>
