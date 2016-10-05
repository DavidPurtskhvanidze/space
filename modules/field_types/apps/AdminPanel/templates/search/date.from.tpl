{i18n->getDateFormat assign="date_format"}

<div class="input-group">
  <input type="text" name="{$id}[not_earlier]" value="{$value.not_earlier|escape}" class="form-control date-picker" data-date-format="mm-dd-yyyy">
</div>
<div class="help-block">
  [[date format]]:'{$date_format}'
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $('.date-picker').datepicker();
  });
</script>
