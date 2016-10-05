<input type="text" name="{$id}[not_later]" value="{$value.not_later|escape}" class="form-control" />
{capture name="date_format_example" assign="date_format_example"}{tr type="date"}now{/tr}{/capture}
{i18n->getDateFormat assign="date_format"}
<p>[[date format: '$date_format', for example: '$date_format_example']]</p>
