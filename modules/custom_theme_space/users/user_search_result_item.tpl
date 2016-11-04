{capture assign="UriFilePart"}{$user.DealershipName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.FirstName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.LastName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html{/capture}
<div class="col-lg-3 col-md-4 col-sm-6">
	<div class="universal-user-box sweet-gray-bg m-bottom-30">
		<div class="universal-user-box-head">
			<div class="text-center">
				<a class="wb" href="{page_path id='users'}{$user.id}/{$UriFilePart}">
					{if $user.ProfilePicture.file_url && $user.ProfilePicture.ProfilePicture.url != '/ProfilePictures/ProfilePicture/missing.png'}
						<img class="img-responsive center-block universal-user-box-avatar" src="{$user.ProfilePicture.ProfilePicture.url}" alt="[[Profile Picture:raw]]"/>
					{else}
						<img src="{url file='main^user_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable img-responsive center-block"/>
					{/if}
					<span class="full-prof-text hidden-xs">View <br> Full Profile</span>
				</a>
				<a class="visible-xs-block default-button wb" href="{page_path id='users'}{$listing.user.sid}">
					[[View Full Profile]]
				</a>
			</div>
		</div>
		<div class="universal-user-box-body">
			<ul class="list-unstyled">
				<li>
					{$user.DealershipName}
				</li>
				<li>
					{$user.FirstName} {$user.LastName}
				</li>
				{if $user.DisplayEmail.isTrue}
					<li class="email-string">
						<span title="Send Email">{mailto address=$user.email encode="javascript"}</span>
					</li>
				{/if}
				<hr>
				<li>
					<address>
						{$user.Address} <br> {$user.City} {$user.State}
					</address>
				</li>
			</ul>
		</div>
		<div class="universal-user-box-footer">
			<div class="row">
				<div class="{if $user.PhoneNumber != ''}col-xs-6{else}col-xs-12{/if}">
					<a class="default-button wb" title="Contact Seller" data-placement="top" data-toggle="tooltip" onclick='return openDialogWindow("[[Contact Seller]]", this.href, 560)' href="{page_path id='users_contact'}{$user.id}/{$UriFilePart}">
						{*<img src="{url file="main^img/mail_icn.png"}" alt="">*}
						<span class="glyphicon glyphicon-envelope"></span>
					</a>
				</div>
				<div class="col-xs-6">
					{if $user.PhoneNumber != ''}
						<a class="default-button wb" data-placement="top" data-toggle="tooltip" href="tel:{$user.PhoneNumber}" title="Call to {$user.PhoneNumber}">
							{*<img src="{url file="main^img/phone_icn.png"}" alt="">*}
							<span class="glyphicon glyphicon-earphone"></span>
						</a>
					{/if}
				</div>
			</div>


		</div>
	</div>
</div>



{*<div class="col-md-4 col-sm-6">*}
	{*<div class="users-box-item sweet-gray-bg">*}
		{*<div class="users-box-item-head">*}
			{*<div class="row">*}
				{*<div class="col-xs-4 text-center">*}
					{*<a class="wb" href="{page_path id='users'}{$user.id}/{$UriFilePart}">*}
					{*{if $user.ProfilePicture.file_url}*}
						{*<img class="img-responsive center-block" src="{$user.ProfilePicture.ProfilePicture.url}" alt="[[Profile Picture:raw]]"/>*}
					{*{else}*}
						{*<img src="{url file='main^user_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable img-responsive center-block"/>*}
					{*{/if}*}
					{*</a>*}
				{*</div>*}
				{*<div class="col-xs-8">*}
					{*<div class="users-box-item-head-user-name">*}
						{*{$user.DealershipName}*}
					{*</div>*}
					{*<div class="users-box-item-head-user-full-name">*}
						{*{$user.FirstName} {$user.LastName}*}
					{*</div>*}
					{*<div class="row">*}
						{*<div class="col-xs-6">*}
							{*<a class="users-box-item-head-control" href="{page_path id='users_listings'}{$user.id}/{$UriFilePart}">*}
								{*<span>Listings</span><span><img src="{url file="main^img/list-icn.png"}" alt=""></span>*}
							{*</a>*}
						{*</div>*}
						{*<div class="col-xs-6">*}
							{*<a class="users-box-item-head-control" href="{page_path id='users'}{$user.id}/{$UriFilePart}">*}
								{*<span>Profile</span><span><img src="{url file="main^img/user_icn.png"}" alt=""></span>*}
							{*</a>*}
						{*</div>*}
					{*</div>*}
				{*</div>*}
			{*</div>*}
		{*</div>*}
		{*<div class="users-box-item-body">*}
			{*<address class="fieldValue Location">*}
				{*{$user.Address} <br> {$user.City} {$user.State}*}
			{*</address>*}
		{*</div>*}
		{*<div class="users-box-item-footer">*}
			{*<div class="row">*}
				{*<div class="col-sm-8 col-xs-7">*}
					{*<div><b>{$user.PhoneNumber}</b></div>*}
					{*{if $user.DisplayEmail.isTrue}*}
					{*<div title="Send Email">{mailto address=$user.email encode="javascript"}</div>*}
					{*{/if}*}
					{*<div title="{$user.DealershipWebsite}"><a href="{$user.DealershipWebsite}">{$user.DealershipName} [[Website]]</a></div>*}
				{*</div>*}
				{*<div class="col-sm-4 col-xs-5">*}
					{*<div class="row">*}
						{*<div class="col-xs-6">*}
							{*<a class="users-box-item-footer-contact-seller pull-right" title="Contact Seller" onclick='return openDialogWindow("[[Contact Seller]]", this.href, 560)' href="{page_path id='users_contact'}{$user.id}/{$UriFilePart}">*}
								{*<img src="{url file="main^img/mail_icn.png"}" alt="">*}
							{*</a>*}
						{*</div>*}
						{*{if $user.PhoneNumber != ''}*}
						{*<div class="col-xs-6">*}
							{*<a class="users-box-item-footer-call-seller pull-right" href="tel:{$user.PhoneNumber}" title="Call to {$user.PhoneNumber}">*}
								{*<img src="{url file="main^img/phone_icn.png"}" alt="">*}
							{*</a>*}
						{*</div>*}
						{*{/if}*}
					{*</div>*}

				{*</div>*}
			{*</div>*}
		{*</div>*}
	{*</div>*}
{*</div>*}

