{capture name="input_text_field_from" assign="input_text_field_from"} <input type="text" name="{$id}[not_less]" class="searchIntegerLess" value="" id="{$id}" readonly="readonly" /> {/capture}
{capture name="input_text_field_to" assign="input_text_field_to"}   <input type="text" name="{$id}[not_more]" class="searchIntegerMore" value="" readonly="readonly" /> {/capture}

{if isset($parameters.min)}
	{assign var=min value=$parameters.min}
{elseif isset($minimum)}
	{assign var=min value=$minimum}
{else}
	{assign var=min value=0}
{/if}

{if isset($parameters.max)}
	{assign var=max value=$parameters.max}
{elseif isset($maximum)}
	{assign var=max value=$maximum}
{else}
	{assign var=max value=0}
{/if}

<div class="integerField">
	<div class="captions">
		<div class="minRange">[[min]]</div>
		<div class="maxRange">[[max]]</div>
	</div>
	<div class="sliderContainer">
		<div class="{$id|lower}SliderRange"></div>
	</div>
	<div class="integerInputs">
		<div class="minRange">{$input_text_field_from}</div>
		<div class="maxRange">{$input_text_field_to}</div>
	</div>
</div>
<script>
	$(function () {
		var minMaxValues = get{$id}MinMaxValues();

		//Setting Min and Max values to text input fields
		$('input[name="{$id}[not_less]"]').val(minMaxValues['min']);
		$('input[name="{$id}[not_more]"]').val(minMaxValues['max']);

		//Initializing slider
		$('.{$id|lower}SliderRange').slider({
			range: true,
			min: {$min},
			max: {$max},
			values: [ minMaxValues['min'], minMaxValues['max']],
			slide: function (event, ui) {
				$('input[name="{$id}[not_less]"]').val(ui.values[ 0 ]);
				$('input[name="{$id}[not_more]"]').val(ui.values[ 1 ]);
			}
		});

		var cleaner = new Object();
		cleaner.clearAllSelection = function () {
			$('.{$id|lower}SliderRange').slider("values", [{$min},{$max}]); // sets first handle (index 0) to 50

			$('input[name="{$id}[not_less]"]').val({$min});
			$('input[name="{$id}[not_more]"]').val({$max});
		};
		resetObserver[resetObserver.length]=cleaner;

		$(".{$id|lower}SliderRange").on("slidestop", function(){
			$('input[name="{$id}[not_less]"]').change();
		});
	});

	function get{$id}MinMaxValues() {
		var min;
		var max;
		if ("{$value.not_less|escape}" == "") { //if value from request is empty
			min = {$min};            //setting the default min range value
		}
		else {
			min = parseInt("{$value.not_less|escape}"); //otherwise setting value from request
		}

		if ("{$value.not_less|escape}" == "") {
			max = {$max};
		}
		else {
			max = parseInt("{$value.not_more|escape}");
		}
		return { //Returning object
			min: min,
			max: max
		}
	}




</script>
