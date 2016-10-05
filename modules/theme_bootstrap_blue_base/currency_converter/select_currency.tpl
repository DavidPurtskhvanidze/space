<div class="dropdown currencyWrapper">
	<a id="CurrencyMenu" data-toggle="dropdown" href="#">
        <span class="text">
		    {$selectedCurrencyCode}
        </span>
        <i class="fa fa-chevron-down"></i>
	</a>
	<ul class="dropdown-menu custom dropdown-menu-left" role="menu" aria-labelledby="CurrencyMenu">
		{foreach from=$availableCurrenciesList key="currencyCode" item="currencyCaption"}
			<li role="presentation">
				<a role="menuitem" tabindex="-1" class="currencyConvert"  href="{page_path module='currency_converter' function='currency_selection'}?action=change&amp;call_back_uri={$callBackURI|urlencode}&amp;currency_code={$currencyCode}"{if $currencyCode == $selectedCurrencyCode} class="selected"{/if}>
					<span class="code">{$currencyCode}</span> <span class="caption">{$currencyCaption}</span>
				</a>
			</li>
		{/foreach}
	</ul>
</div>
<script type="text/javascript">
    $(document).ready(function()
        {
            $("a.currencyConvert").click(function(event){
                event.preventDefault();
                if(!$(this).hasClass('selected')){
                    var currencyCode = $(this).find('span.code').text();
                    if (changeCurrency(currencyCode))
                    {
                        $('#CurrencyMenu').text(currencyCode).append('<span class="caret"></span>');
                        $("a.currencyConvert").removeClass('selected');
                        $(this).addClass('selected');
                    }
                }
            });
        }
    )
</script>
