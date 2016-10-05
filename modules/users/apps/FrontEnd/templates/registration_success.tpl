{assign var='loginLink' value={page_path id='user_login'}}
{assign var='addListingLink' value={page_path id='listing_add'}}

<p class="success">
	<span>[[Thank you! You have successfully registered on our website. You can start using all the functions available for the registered users.]]</span>
</p>
<p>[[Please <a href="$loginLink">log in</a> and then you'll be able to <a href="$addListingLink">add listings</a>.]]</p>
{require component="jquery" file="jquery.js"}
