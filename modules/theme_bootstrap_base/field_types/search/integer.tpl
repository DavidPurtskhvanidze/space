{capture name="input_text_field_from" assign="input_text_field_from"} <input type="text" name="{$id}[not_less]" class="searchIntegerLess form-control" value="{$value.not_less|escape}" placeholder="[[Minimum:raw]]"> {/capture}
{capture name="input_text_field_to" assign="input_text_field_to"}   <input type="text" name="{$id}[not_more]" class="searchIntegerMore form-control" value="{$value.not_more|escape}" placeholder="[[Maximum:raw]]"> {/capture}

<div class="row">
	<div class="col-xs-6">
		{$input_text_field_from}
	</div>
	<div class="col-xs-6">
		{$input_text_field_to}
	</div>
</div>

