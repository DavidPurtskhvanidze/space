{if $facebookIsSetUp || $twitterIsSetUp}
	<div class="listingRepostSettings">
		<h3>[[Re-post Listings]]</h3>
		{if !$userCanAddListings}
			{capture assign="changeUserGroupLink"}{page_path module='users' function='change_user_group'}{/capture}
			{capture assign="onClickAction"}return openDialogWindow('[[Change User Group:raw]]', this.href, 400, true){/capture}
			[[Our website can be configured to re-post your listings on your Facebook and Twitter pages.]]
			[[Your current User Group is not allowed to add listings. Please <a href="$changeUserGroupLink" onclick="$onClickAction">change your User Group</a> to be able to add listings.]]
		{else}
			<table>
				{if $twitterIsSetUp}
					<tr>
						<td>[[Twitter repost is {if $twitterStatus}enabled{else}disabled{/if}.]]</td>
						<td>
							{if $twitterStatus}
								<a href="{page_path module='listing_repost' function='listing_repost_settings'}?provider=Twitter&action=disable">[[Disable]]</a>
							{else}
								<a href="{page_path module='listing_repost' function='listing_repost_settings'}?provider=Twitter&action=enable">[[Enable]]</a>
							{/if}
						</td>
					</tr>
				{/if}
			</table>
		{/if}
	</div>
{/if}

{require component="jquery" file="jquery.js"}
{require component="jquery-maxlength" file="jquery.maxlength.js"}
{require component="js" file="script.maxlength.js"}
{include file="miscellaneous^dialog_window.tpl"}
