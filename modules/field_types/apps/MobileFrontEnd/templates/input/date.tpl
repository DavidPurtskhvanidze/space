{if $hasError}<div class="error validation">{$error}</div>{/if}
<input type="text" value="{$value|escape}" class="inputString{if $maxlength > 0} maxlength{/if}" name="{$id}" id="{$id}"{if $maxlength > 0} maxlength={$maxlength}{/if}/>
