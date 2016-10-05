{display_success_messages}
{if isset($REQUEST.action)}
	<div class="userSearchResultsPage">
		{if $search.total_found > 0}
			<div class="searchResultHeader">
				<ul class="searchControls multilevelMenu">
					<li class="objectsPerPageSelector">
						{include file="objects_per_page_selector.tpl" listing_search=$listing_search url='?action=restore'}
					</li>
				</ul>
			</div>
			<div class="searchResults">
				{foreach from=$users item=user}
					<div class="searchResultItem">
						<div class="fieldValue fieldValueProfilePicture">
							{if $user.ProfilePicture.exists && $user.ProfilePicture.ProfilePicture.name}
								<img src="{$user.ProfilePicture.ProfilePicture.url}" alt="[[Profile Picture:raw]]" />
							{else}
								<img src="{url file='main^user_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable" />
							{/if}
						</div>
						<div class="detailsAndUserProfileControlsWrapper">
							<div class="details">
								<ul>
									<li>
										<span class="fieldValue fieldValueDealershipName">{$user.DealershipName}</span>
									</li>
									<li>
										<span class="fieldValue fieldValueLocation">{$user.Address} {$user.City} {$user.State}</span>
									</li>
									{if $user.DealershipWebsite.exists && $user.DealershipWebsite.isNotEmpty}
									<li>
										<span class="fieldValue fieldValueDealershipWebsite"><a href="{$user.DealershipWebsite}">{$user.DealershipName} [[Website]]</a></span>
									</li>
									{/if}
									{if $user.DisplayEmail.isTrue}
										<li>
											<span class="fieldValue fieldValueEmail">{mailto address=$user.email encode="javascript"}</span>
										</li>
									{/if}
									<li>
										<span class="fieldCaption fieldCaptionPhoneNumber">[[Phone]]:</span>
										<span class="fieldValue fieldValuePhoneNumber">{$user.PhoneNumber}</span>
									</li>
								</ul>
							</div>
							<div class="userProfileControls">
								<ul>
									<li>
										<a href="{page_path id='users_listings'}{$user.id}/{$user.DealershipName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.FirstName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.LastName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html">[[View All Listings]]</a>
									</li>
									<li>
										<a onclick='return openDialogWindow("[[Contact Seller]]", this.href, 560)' href="{page_path id='users_contact'}{$user.id}/{$user.DealershipName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.FirstName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.LastName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html">[[Contact Seller]]</a>
									</li>
									<li>
										<a href="{page_path id='users'}{$user.id}/{$user.DealershipName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.FirstName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.LastName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html">[[View Full Profile]]</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				{/foreach}
				{include file="miscellaneous^dialog_window.tpl"}
			</div>
			{include file="page_selector.tpl" current_page=$search.current_page pages_number=$search.pages_number url='?action=restore'}
			{include file="miscellaneous^multilevelmenu_js.tpl"}
		{else}
			<p>[[There are no user profiles that match your search criteria.]]</p>
		{/if}
	</div>
{/if}
