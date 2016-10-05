{if $hasError}<div class="error validation">{$error}</div>{/if}
<input type="hidden" name="{$id}" id="{$id}" value="0" />
<input class="{if $hasError}has-error{/if}" type="checkbox" name="{$id}" {if $value}checked{/if} value="1" {if $hasError}data-error="{$error}"{/if}/>
