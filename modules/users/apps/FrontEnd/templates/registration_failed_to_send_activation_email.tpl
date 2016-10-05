{assign var='contactLink' value=$GLOBALS.site_url|cat:'/contact/'}
<h1>[[Registration]]</h1>
<p class="error">[[Failed to send activation email.]]
    [[Please <a href="$contactLink">contact</a> site administrator to complete the registration.]]
</p>
