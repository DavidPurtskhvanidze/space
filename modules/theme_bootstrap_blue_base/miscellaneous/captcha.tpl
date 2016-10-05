<div class="row">
    <div class="col-sm-4">
        <img id="Captcha" src="{page_path module='miscellaneous' function='captcha_image'}?f={"1000"|mt_rand:10000}" alt="captcha" />
        <div class="space-20 visible-xs"></div>
    </div>
    <div class="col-sm-8">
        <input type="text" name="{$id}" class="form-control {if $hasError}has-error{/if}" {if $hasError}data-error="{$error}"{/if}>
        <a href="#" onclick="document.getElementById('Captcha').src = '{page_path module='miscellaneous' function='captcha_image'}?f=' + Math.random(); return false;">[[Reload Image]]</a><br />
    </div>
</div>
