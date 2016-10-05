
<input type="text" name="{$id}[geo][location]" value="{$value.location|escape}" class="form-control">
<br />
<select name="{$id}[geo][radius]" class="form-control">

	<option value="any">Any distance</option>
	<option value="10" {if $value.radius == 10}selected{/if}>Within 10 miles</option>
	<option value="20" {if $value.radius == 20}selected{/if}>Within 20 miles</option>
	<option value="30" {if $value.radius == 30}selected{/if}>Within 30 miles</option>
	<option value="40" {if $value.radius == 40}selected{/if}>Within 40 miles</option>
	<option value="50" {if $value.radius == 50}selected{/if}>Within 50 miles</option>

</select>
