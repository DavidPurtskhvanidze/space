<input type="text" value="{$value|escape}" name="{$id}" placeholder="{$placeholder}" class="form-control {if $hasError}has-error tooltip-error{/if}" {if $hasError}data-rel="tooltip" data-placement="top" title="{$error}"{/if}>