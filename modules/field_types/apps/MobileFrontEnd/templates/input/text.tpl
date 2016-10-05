{if $hasError}<div class="error validation">{$error}</div>{/if}
<textarea class="inputText{if $maxlength > 0} maxlength{/if}" name="{$id}" id="{$id}"{if $maxlength > 0} maxlength={$maxlength}{/if}>{$value}</textarea>
