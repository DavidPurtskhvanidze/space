{*todo: apparently this template is not used. Delete if it is true *}
<div class="editPicturePage">
	<div class="breadcrumbs">
		<a href="{$GLOBALS.site_url}/manage_listings/?restore=1">[[Manage Listings]]</a> &gt;
		<a href="{page_path id='edit_listing'}?listing_id={$listing_id}">[[Edit Listing]]</a> &gt;
		<a href="{page_path module='classifieds' function='manage_pictures'}?listing_id={$listing_id}">[[Manage Pictures]]</a>
	</div> 
	<h1>[[Edit Picture]]</h1>

	<form method="post">
        {CSRF_token}
		<input type="hidden" name="picture_sid" value="{$picture_sid}" />
		<input type="hidden" name="listing_id" value="{$listing_id}" />
		<table class="properties">
			<tr>
				<td>[[Picture]]</td>
				<td>{listing_image pictureInfo=$picture thumbnail=1}</td>
			</tr>

			<tr>
				<td>[[Caption]]</td>
				<td><input type="text" name="picture_caption" value="{$picture.caption}"></td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td><input type="submit" value="[[Save:raw]]"></td>
			</tr>
		</table>
	</form>
</div>
