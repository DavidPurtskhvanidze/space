{capture name="input_text_field_from" assign="input_text_field_from"} <input type="hidden" name="{$id}[not_less]" class="searchIntegerLess" value="" id="{$id}" /> {/capture}
{capture name="input_text_field_to" assign="input_text_field_to"}   <input type="hidden" name="{$id}[not_more]" class="searchIntegerMore" value="" /> {/capture}

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

<div class="moneyField integerField" data-id="{$id}">
	<div class="captions">
		<div class="minRange">[[min]]</div>
		<div class="maxRange">[[max]]</div>
	</div>
	<div class="sliderContainer">
		<div class="{$id|lower}SliderRange"></div>
	</div>
	<div class="integerInputs {$id|lower}ValuesToDisplay">
		<div class="minRange"><input type="text" class="searchIntegerLess" value="" disabled="disabled" /></div>
		<div class="maxRange"><input type="text" class="searchIntegerMore" value="" disabled="disabled" /></div>
	</div>
	{$input_text_field_from}
	{$input_text_field_to}
</div>
<script>
	//Global variables may be used by included modules (Currency Converter f.e.)
	money{$id}MinMaxValues = get{$id}SliderMinMaxValues();
	money{$id}MinSliderValue = {$min};
	money{$id}MaxSliderValue = {$max};
	$(function () {
		//Setting Min and Max values to text input fields and hidden inputs
		$('input[name="{$id}[not_less]"]:hidden').val(money{$id}MinMaxValues['min']);
		$('input[name="{$id}[not_more]"]:hidden').val(money{$id}MinMaxValues['max']);

		$('.{$id|lower}ValuesToDisplay .minRange input[type="text"]').val(addCommas(money{$id}MinMaxValues['min']));
		$('.{$id|lower}ValuesToDisplay .maxRange input[type="text"]').val(addCommas(money{$id}MinMaxValues['max']));

		// Slider Initializing
		$('.{$id|lower}SliderRange').slider({
			range: true,
			min: 0,
			max: 100,
			values: [get{$id}SliderPositionByValue( money{$id}MinMaxValues['min']),get{$id}SliderPositionByValue(money{$id}MinMaxValues['max'])],
			slide: function (event, ui) {
				var minPrice = get{$id}ValueBySliderPosition(ui.values[ 0 ]);
				var maxPrice = get{$id}ValueBySliderPosition(ui.values[ 1 ]);


				$('input[name="{$id}[not_less]"]:hidden').val(minPrice);
				$('input[name="{$id}[not_more]"]:hidden').val(maxPrice);

				$('.{$id|lower}ValuesToDisplay .minRange input[type="text"]').val(addCommas(minPrice)).dblclick();
				$('.{$id|lower}ValuesToDisplay .maxRange input[type="text"]').val(addCommas(maxPrice)).dblclick();
			}
		});

		//Form Resetting
		var cleaner = new Object(); //will be used for from resetting

		cleaner.clearAllSelection = function () {
			$('.{$id|lower}SliderRange').slider("values", [0,100]); // sets first handle (index 0) to 50

			$('input[name="{$id}[not_less]"]:hidden').val(money{$id}MinSliderValue);
			$('input[name="{$id}[not_more]"]:hidden').val(money{$id}MaxSliderValue);

//			$('.valuesToDisplay .minRange input[type="text"]').val(moneyMinSliderValue);
//			$('.valuesToDisplay .maxRange input[type="text"]').val(moneyMaxSliderValue);
			$('.valuesToDisplay .minRange input[type="text"]').val(addCommas(money{$id}MinSliderValue));
			$('.valuesToDisplay .maxRange input[type="text"]').val(addCommas(money{$id}MaxSliderValue));
		};

		resetObserver[resetObserver.length]=cleaner;
		//End of Form Resetting

		//Form submit triggering
		$(".{$id|lower}SliderRange").on("slidestop", function(){
			$('input[name="{$id}[not_less]"]').change();
		});

	});


	//Get slider min max values from request if any
	function get{$id}SliderMinMaxValues() {
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

	// Used for getting values in exponential progress
	function get{$id}ValueBySliderPosition(position) {
		if (position == 0)
			return 0;
		var minSliderPosition = 0;
		var maxSliderPosition = 100;
		var minValue = money{$id}MinSliderValue;
		if (minValue == 0)// Math.log(0) returns -infinitive. Handling that situation
		{
			minValue = 1;
		}
		minValue = Math.log(minValue);
		var maxValue = Math.log(money{$id}MaxSliderValue);
		// calculate adjustment factor
		var scale = (maxValue-minValue) / (maxSliderPosition-minSliderPosition);

		return Math.exp(minValue + scale*(position-minSliderPosition)).toFixed(0);
	}

	function get{$id}SliderPositionByValue(value) {
		if (value == 0)
			return 0;
		var minSliderPosition = 0;
		var maxSliderPosition = 100;
		var minValue = money{$id}MinSliderValue;
		if (minValue == 0)
		{
			minValue = 1;
		}
		minValue = Math.log(minValue);
		var maxValue = Math.log(money{$id}MaxSliderValue);
		var scale = (maxValue-minValue) / (maxSliderPosition-minSliderPosition);
		return (Math.log(value)-minValue) / scale + minSliderPosition;
	}


	function addCommas(nStr) {
        var thousandsSeparator = '{i18n->getCurrentLanguageThousandsSeparator}';
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{ldelim}3{rdelim})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + thousandsSeparator + '$2');
		}
		return x1 + x2;
	}
</script>
{extension_point name='modules\classifieds\apps\FrontEnd\IRefineSearchMoneyFormElement' manipulatedTypeId=$id signNum=$signs_num}
