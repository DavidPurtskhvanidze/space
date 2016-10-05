{if isset($REQUEST.action)}
	<div class="userSearchResultsPage">
		<h1 class="usersHeader">[[Search Results]]</h1>
		{if $search.total_found > 0}
			<div class="searchResults">
				{foreach from=$users item=user name=users}
					<div class="searchResultItem {if $smarty.foreach.users.last}lastItem{/if}">
						<div class="itemPicture">
							{if $user.ProfilePicture.exists && $user.ProfilePicture.ProfilePicture.name}
								<img src="{$user.ProfilePicture.ProfilePicture.url}" alt="[[Profile Picture:raw]]" />
							{else}
								<img src="{url file='main^user_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable" />
							{/if}
						</div>
						<div class="detailsAndItemControlsWrapper">
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
							<div class="itemControls">
								<ul>
									<li>
										<a href="{page_path id='users_listings'}{$user.id}/{$user.DealershipName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.FirstName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.LastName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html">[[View All Listings]]</a>
									</li>
									<li>
										<a href="{page_path id='users_contact'}{$user.id}/{$user.DealershipName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.FirstName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}-{$user.LastName|regex_replace:"/[\\/\\\:*?\"<>|%#$\s]/":"-"}.html">[[Contact Seller]]</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				{/foreach}
			</div>
			{include file="page_selector.tpl" current_page=$search.current_page pages_number=$search.pages_number}
		{else}
			<p>[[There are no user profiles that match your search criteria.]]</p>
		{/if}
		<div class="searchResultsLinks">
			<a href="{page_path id='users_search'}?DealershipName[equal]={$REQUEST.DealershipName.equal}&City[equal]={$REQUEST.City.equal}&State[equal]={$REQUEST.State.equal}">[[Modify search]]</a>
		</div>
	</div>
{/if}
