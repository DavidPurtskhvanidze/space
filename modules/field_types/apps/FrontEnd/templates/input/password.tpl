{if $parameters.id_prefix}
	{$id_attribute = " id=\"{$parameters.id_prefix}_{$id}\""}
{else}
	{$id_attribute = " id=\"input_{$id}\""}
{/if}
<input type="password" name="{$id}[original]" {$id_attribute}
       class="form-control {if $hasError}has-error{/if}"
       {if $hasError}data-error="{$error}"{/if} /><br />
<input type="password" name="{$id}[confirmed]"
       class="form-control {if $hasError}has-error{/if}"
       {if $hasError}data-error="{$error}"{/if} />
<span class="passwordConfirmText">[[Confirm Password]]</span>
