<select name="{$id}[tree][0]" id="{$id}[tree][0]"
        class="searchTreeLevel1 form-control {if $hasError}has-error{/if}"
        {if $hasError}data-error="{$error}"{/if}>
	<option value="">[[Miscellaneous!Any:raw]] [[FormFieldCaptions!{$levels_captions.0}:raw]]</option>
	{assign var='parent' value=0}
	{foreach from=$tree_values.$parent item=tree_value}
	<option value="{$tree_value.sid|escape}"{if $value.0 == $tree_value.sid} selected="selected"{/if}>{tr mode="raw" domain="Property_$id"}{$tree_value.caption}{/tr}</option>
	{/foreach}
</select>

<script type="text/javascript">
    $(document).ready(function() {
        $("select[id='{$id}\[tree\]\[0\]']").change(function() {
            var targetSelectBox = $("select[name='{$id}\[tree\]\[1\]']");
            var optionAny;
            $.ajax({
                url: '{page_path module='field_types' function='fetch_ajax_tree_data'}',
                crossDomain: false,
                dataType: "json",
                data: {
                    field_sid : {$sid},
                    parent_sid : $(this).val(),
                    object: 'classifieds'
                },
                success: function(data) {
                    targetSelectBox.append(optionAny);
	                $.each(data, function (index, v) {
		                targetSelectBox.append(new Option(v[1], v[0]));
	                });
                },
                beforeSend: function(){
                    optionAny = targetSelectBox.find('option:first');
                    targetSelectBox.find('option').remove();
                    targetSelectBox.prop('disabled', true);
                    targetSelectBox.addClass('qs-ajax-loader');
                },
                complete: function(){
                    targetSelectBox.prop('disabled', false);
                    targetSelectBox.removeClass('qs-ajax-loader');
                }
            });
        });
    });
</script>
