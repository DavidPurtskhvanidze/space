<div class="checkbox {if $hasError}has-error{/if}" {if $hasError}data-error="{$error}"{/if}>
  <input type="hidden" name="{$id}" value="0">
  <label>
    <input class="ace ace-switch ace-switch-6" type="checkbox" name="{$id}" {if $value}checked{/if} value="1">
    <span class="lbl"></span>
  </label>
</div>
