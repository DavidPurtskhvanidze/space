{capture name="date_format_example" assign="date_format_example"}{tr type="date"}now{/tr}{/capture}

{capture name="input_text_field_from" assign="input_text_field_from"}
  <div class="input-group input-group-date-picker">
    <input type="text" name="{$id}[not_earlier]" value="{$value.not_earlier|escape}"  class="form-control date-picker" data-date-format="mm-dd-yyyy">
    <div class="input-group-addon">
      <i class="icon-calendar bigger-110"></i>
    </div>
  </div>
{/capture}

{capture name="input_text_field_to" assign="input_text_field_to"}
  <div class="input-group input-group-date-picker">
    <input type="text" name="{$id}[not_later]" value="{$value.not_later|escape}"  class="form-control date-picker" data-date-format="mm-dd-yyyy">
    <div class="input-group-addon">
      <i class="icon-calendar bigger-110"></i>
    </div>
  </div>
{/capture}



{i18n->getDateFormat assign="date_format"}

<div class="row">
  <div class="col-sm-12 no-padding">
    <div class="col-sm-5 no-padding">
      {$input_text_field_from}
      <div class="help-block">[[date format:]] '{$date_format}', [[for example:]] '{$date_format_example}'</div>
    </div>
    <div class="col-sm-1"></div>
    <div class="col-sm-5 no-padding">
      {$input_text_field_to}
      <div class="help-block">[[date format:]] '{$date_format}', [[for example:]] '{$date_format_example}'</div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
      $("input.date-picker").attr('data-date-format', '{i18n->getRawDateFormat|replace:'%Y':'yyyy'|replace:'%m':'mm'|replace:'%d':'dd'}');
      $('.date-picker').datepicker();
  });
</script>
