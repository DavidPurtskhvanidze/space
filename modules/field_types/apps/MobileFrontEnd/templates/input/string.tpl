{if $hasError}<div class="error validation">{$error}</div>{/if}
<input type="text" value="{$value|escape}" id="{$id}" class="inputString{if $maxlength > 0} maxlength{/if}" name="{$id}"{if $maxlength > 0} maxlength={$maxlength}{/if}/>
