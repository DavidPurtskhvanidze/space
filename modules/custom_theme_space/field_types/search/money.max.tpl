<input type="text" name="{$id}[not_more]" class="searchIntegerMore form-control money" value="{$value.not_more|escape}" placeholder="{$placeholder}" />

{extension_point name='modules\main\apps\FrontEnd\ISearchFormElement' manipulatedTypeId=$id signNum=$signs_num}
