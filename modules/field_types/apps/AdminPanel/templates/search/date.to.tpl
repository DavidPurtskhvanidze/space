<div class="input-group">
  <input type="text" name="{$id}[not_later]" value="{$value.not_later|escape}" class="form-control date-picker" data-date-format="mm-dd-yyyy">
</div>
{i18n->getDateFormat assign="date_format"}

<div class="help-block">
  [[date format]]:'{$date_format}'
</div>
