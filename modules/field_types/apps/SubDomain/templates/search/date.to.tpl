<input type="text" name="{$id}[not_later]" id="{$id}" value="{$value.not_later|escape}" />
<br/>
{capture name="date_format_example" assign="date_format_example"}{tr type="date"}now{/tr}{/capture}
{i18n->getDateFormat assign="date_format"}

[[date format: '$date_format', for example: '$date_format_example']]
