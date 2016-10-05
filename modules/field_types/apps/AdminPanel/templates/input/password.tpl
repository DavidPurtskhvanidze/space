<input type="password" name="{$id}[original]" class="form-control {if $hasError}has-error tooltip-error{/if}" {if $hasError}data-rel="tooltip" data-placement="top" title="{$error}"{/if} />
<input type="password" name="{$id}[confirmed]" class="form-control {if $hasError}has-error tooltip-error{/if}" {if $hasError}data-rel="tooltip" data-placement="top" title="{$error}"{/if} />
<span style="font-size:smaller">[[Confirm Password]]</span>
