<input type="text" name="{$id}[not_less]" class="searchIntegerLess form-control money" value="{$value.not_less|escape}" id="{$id}" placeholder="{$placeholder}" />

{extension_point name='modules\main\apps\FrontEnd\ISearchFormElement' manipulatedTypeId=$id signNum=$signs_num}
