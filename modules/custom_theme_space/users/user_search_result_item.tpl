{capture assign="UriFilePart"}{$user.DealershipName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.FirstName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.LastName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html{/capture}
<div class="col-md-4 col-sm-6">
	<div class="users-box-item sweet-gray-bg">
		<div class="users-box-item-head">
			<div class="row">
				<div class="col-xs-4 text-center">
					<a class="wb" href="{page_path id='users'}{$user.id}/{$UriFilePart}">
					{if $user.ProfilePicture.file_url}
						<img class="img-responsive center-block" src="{$user.ProfilePicture.ProfilePicture.url}" alt="[[Profile Picture:raw]]"/>
					{else}
						<img src="{url file='main^user_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable img-responsive center-block"/>
					{/if}
					</a>
				</div>
				<div class="col-xs-8">
					<div class="users-box-item-head-user-name">
						{$user.DealershipName}
					</div>
					<div class="users-box-item-head-user-full-name">
						{$user.FirstName} {$user.LastName}
					</div>
					<div class="row">
						<div class="col-xs-6">
							<a class="users-box-item-head-control" href="{page_path id='users_listings'}{$user.id}/{$UriFilePart}">
								<span>Listings</span><span><img src="{url file="main^img/list-icn.png"}" alt=""></span>
							</a>
						</div>
						<div class="col-xs-6">
							<a class="users-box-item-head-control" href="{page_path id='users'}{$user.id}/{$UriFilePart}">
								<span>Profile</span><span><img src="{url file="main^img/user_icn.png"}" alt=""></span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="users-box-item-body">
			<address class="fieldValue Location">
				{$user.Address} <br> {$user.City} {$user.State}
			</address>
		</div>
		<div class="users-box-item-footer">
			<div class="row">
				<div class="col-sm-8 col-xs-7">
					<div><b>{$user.PhoneNumber}</b></div>
					{if $user.DisplayEmail.isTrue}
					<div title="Send Email">{mailto address=$user.email encode="javascript"}</div>
					{/if}
					<div title="{$user.DealershipWebsite}"><a href="{$user.DealershipWebsite}">{$user.DealershipName} [[Website]]</a></div>
				</div>
				<div class="col-sm-4 col-xs-5">
					<div class="row">
						<div class="col-xs-6">
							<a class="users-box-item-footer-contact-seller pull-right" title="Contact Seller" onclick='return openDialogWindow("[[Contact Seller]]", this.href, 560)' href="{page_path id='users_contact'}{$user.id}/{$UriFilePart}">
								<img src="{url file="main^img/mail_icn.png"}" alt="">
							</a>
						</div>
						{if $user.PhoneNumber != ''}
						<div class="col-xs-6">
							<a class="users-box-item-footer-call-seller pull-right" href="tel:{$user.PhoneNumber}" title="Call to {$user.PhoneNumber}">
								<img src="{url file="main^img/phone_icn.png"}" alt="">
							</a>
						</div>
						{/if}
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

