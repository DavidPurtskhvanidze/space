<div class="captchaSecurityCode">
	{if $hasError}<div class="error validation">{$error}</div>{/if}
	<img id="Captcha" src="{page_path module='miscellaneous' function='captcha_image'}?f={"1000"|mt_rand:10000}" alt="captcha" />
	<span>
		<a href="#" onclick="document.getElementById('Captcha').src = '{page_path module='miscellaneous' function='captcha_image'}?f=' + Math.random(); return false;">[[Reload Image]]</a>
	</span>
	<input type="text" id="security_code" name="{$id}" />
</div>
