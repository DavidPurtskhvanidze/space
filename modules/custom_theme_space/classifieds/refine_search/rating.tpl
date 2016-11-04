<select class="form-control" name="{$id}[not_less]">
    <option value="">[[Any Rating]]</option>
    <option value="1"{if $value.not_less == 1} selected="SELECTED"{/if}> [[1+]]</option>
    <option value="2"{if $value.not_less == 2} selected="SELECTED"{/if}>[[2+]]</option>
    <option value="3"{if $value.not_less == 3} selected="SELECTED"{/if}>[[3+]]</option>
    <option value="4"{if $value.not_less == 4} selected="SELECTED"{/if}>[[4+]]</option>
    <option value="5"{if $value.not_less == 5} selected="SELECTED"{/if}>[[5]]</option>
</select>
