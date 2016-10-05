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
                {include file="user_search_result_item_detail.tpl"}
			</div>
		</div>
	</div>
</div>
