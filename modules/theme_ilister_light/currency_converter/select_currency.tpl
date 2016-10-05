<div class="currencySelector">
    <a href="#" class="inactive">{$selectedCurrencyCode}</a>
	<ul class="hidden">
	{foreach from=$availableCurrenciesList key="currencyCode" item="currencyCaption"}
		<li>
			<a href="{page_path module='currency_converter' function='currency_selection'}?action=change&amp;call_back_uri={$callBackURI|urlencode}&amp;currency_code={$currencyCode}"{if $currencyCode == $selectedCurrencyCode} class="selected"{/if}>
				<span class="code">{$currencyCode}</span> <span class="caption">{$currencyCaption|truncate:20:'...':true}</span>
			</a>
		</li>
	{/foreach}
	</ul>
</div>

{require component="jquery" file="jquery.js"}
<script type="text/javascript">
    $(document).ready(function(){
	    // init
	    $(".currencySelector > a").addClass("inactive");
	    $(".currencySelector > ul").hide();

	    // switch classes 'active' and 'inactive' on link and dispay or hide list of currencies
	    $(".currencySelector > a").click(function(){
		    $(this).toggleClass("active");
		    $(this).toggleClass("inactive");
		    $(".currencySelector > ul").slideToggle('fast');
		    return false;
	    });
    });
</script>
