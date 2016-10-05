{$element_id = $id}
{if $parameters.element_id_prefix}
	{$element_id = "`$parameters.element_id_prefix`_`$element_id`"}
{/if}
{if !$autocomplete_service_name && !$autocomplete_method_name}
	{$autocomplete_service_name = $parameters.autocomplete_service_name}
	{$autocomplete_method_name = $parameters.autocomplete_method_name}
{/if}
<input type="text" id="{$element_id}" name="{$id}[like]" class="form-control stringWithAutocomplete {$id}"{if $maxlength > 0} maxlength="{$maxlength}"{/if} value="{$value.like|escape}" />

{require component="jquery" file="jquery.js"}
{require component="jquery-ui" file="jquery-ui.js"}
{require component="jquery-ui" file="css/smoothness/jquery-ui.css"}

<script type="text/javascript">
	$(document).ready(function(){
		$("#{$element_id}").autocomplete({
			source: function(request, response){
				$.ajax({
					url: '{page_path module='field_types' function='fetch_autocomplete_data'}',
					crossDomain: false,
					dataType: "json",
					data: {
						keyword: request.term,
						max_items: '{$parameters.max_items|default:10}',
						{if $parameters.preselection_fields}
							form_field_values : this.element.closest('form').serialize(),
							preselection_fields : ['{$parameters.preselection_fields|implode:"','"}'],
						{/if}
						{if $autocomplete_service_name && $autocomplete_method_name}
							autocomplete_service_name: '{$autocomplete_service_name}',
							autocomplete_method_name: '{$autocomplete_method_name}'
						{else}
							property_data: '{$property_data}'
						{/if}
					},
					success: function(data){
						response($.map(data.options, function(item){
							return {
								label: item.label,
								value: item.value
							}
						}));
					}
				});
			},
			minLength: '{$parameters.min_length|default:2}',
			delay: 1000
		}).data('uiAutocomplete')._renderItem = function(ul, item){
			var regex = new RegExp("(" + this.term + ")", 'i');
			item.label = item.label.replace(
				regex,
				"<span class='autocomplete_keyword'>$1</span>"
			);

			return $( "<li>" )
				.append( "<a>" + item.label + "</a>" )
				.appendTo( ul );
		};
	});
</script>
