<input type="text" id="{$id}" name="{$id}" class="form-control stringWithAutocomplete {if $hasError}has-error tooltip-error{/if}  {$id}" {if $hasError}data-rel="tooltip" data-placement="top" title="{$error}"{/if} {if $maxlength > 0} maxlength="{$maxlength}"{/if} value="{$value|escape}" />

{require component="jquery" file="jquery.js"}
{require component="jquery-ui" file="jquery-ui.js"}
{require component="jquery-ui" file="css/smoothness/jquery-ui.css"}

<script type="text/javascript">
	$(document).ready(function(){
		$("#{$id}").autocomplete({
			source: function(request, response){
				$.ajax({
					url: '{page_path module='field_types' function='fetch_autocomplete_data'}',
					crossDomain: false,
					dataType: "json",
					data: {
						keyword: request.term,
						max_items: '{$parameters.max_items|default:10}',
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
