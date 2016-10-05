<div class="thumbnail item">
	<div class="row">
		<div class="col-md-3">
			<a>
				{if $user.ProfilePicture.file_url}
					<img src="{$user.ProfilePicture.ProfilePicture.url}" alt="[[Profile Picture:raw]]"/>
				{else}
					<img src="{url file='main^user_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable"/>
				{/if}
			</a>
		</div>
		<div class="col-md-9">
			<div class="caption">
				<h3>{$user.DealershipName}</h3>

				<div class="row">
					<div class="col-md-8">
						<dl class="dl-horizontal">
							<dt><span class="fieldCaption FullName">[[FormFieldCaptions!Contact Person]]</span></dt>
							<dd><span class="fieldValue FullName">{$user.FirstName} {$user.LastName}</span></dd>

							<dt><span class="fieldCaption Location">[[FormFieldCaptions!Address]]</span></dt>
							<dd><span class="fieldValue Location">{$user.Address} {$user.City} {$user.State}</span></dd>

							{if $user.DealershipWebsite.exists && $user.DealershipWebsite.isNotEmpty}
								<dt><span class="fieldCaption DealershipWebsite">[[FormFieldCaptions!Website]]</span></dt>
								<dd><span class="fieldValue DealershipWebsite"><a href="{$user.DealershipWebsite}">{$user.DealershipName} [[Website]]</a></span></dd>
							{/if}

							{if $user.DisplayEmail.isTrue}
								<dt><span class="fieldCaption Email">[[FormFieldCaptions!Email]]</span></dt>
								<dd><span class="fieldValue Email">{mailto address=$user.email encode="javascript"}</span></dd>
							{/if}

							<dt><span class="fieldCaption PhoneNumber">[[FormFieldCaptions!Phone]]</span></dt>
							<dd><span class="fieldValue PhoneNumber">{$user.PhoneNumber}</span></dd>
					</div>
					<div class="col-md-4">
						{capture assign="UriFilePart"}{$user.DealershipName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.FirstName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.LastName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html{/capture}
						<ul class="list-unstyled controls pull-right">
							<li class="text-right">
								<a href="{page_path id='users_listings'}{$user.id}/{$UriFilePart}" >[[View All Listings]]</a>
							</li>
							<li class="text-right">
								<a href="{page_path id='users'}{$user.id}/{$UriFilePart}">[[View Full Profile]]</a>
							</li>
							<li>
								<a class="btn btn-danger " onclick='return openDialogWindow("[[Contact Seller]]", this.href, 560)' href="{page_path id='users_contact'}{$user.id}/{$UriFilePart}">[[Contact Seller]]</a>
							</li>
						</ul>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
