<div class="managePictures">
	<div class="hint">
		[[You can add up to $numberOfPicturesAllowed photos to your listing.]]
	</div>
	<ul class="pictures">
		{foreach from=$listing.pictures.collection item="picture"}
			<li id="picture_{$picture.id}">
				<div class="picture">
					<img src="{$picture.file.picture.url}" alt="{$picture.caption}">
				</div>
				<div class="controls">
					<div class="caption">
						<a href="#" data-pk="{$picture.id}">
							{if !empty($picture.caption)}{$picture.caption}{/if}
						</a>
					</div>
					<div class="deleteWrapper">
						<a title="[[Delete:raw]]" class="itemControls delete"
						   href="{page_path module='classifieds' function='picture_actions'}?action=delete&amp;listing_sid={$listing.id}&amp;picture_sid={$picture.id}">
							<img src="{url file='main^icons/bin.png'}" alt="[[Delete:raw]]" />
						</a>
					</div>
				</div>
			</li>
		{/foreach}
		<li class="addPictureForm">
			<div>
				<div class="fileInputButton">
					<input type="file" name="pictures[]" data-url="{page_path module='classifieds' function='picture_actions'}" multiple accept="image/jpeg,image/png,image/gif" />
				</div>
				<div class="error"></div>
                <img class="imgLoading" src="{url file="classifieds^loading.gif"}" alt=""/>
            </div>
		</li>
	</ul>

	{require component="jquery" file="jquery.js"}
    {require component="jquery-ui" file="jquery-ui.js"}

	{require component="jQuery-File-Upload" file="vendor/jquery.ui.widget.js"}
	{require component="jQuery-File-Upload" file="jquery.iframe-transport.js"}
	{require component="jQuery-File-Upload" file="jquery.fileupload.js"}

	{require component="X-editable-jqueryui" file="js/jqueryui-editable.min.js"}
	{require component="X-editable-jqueryui" file="css/jqueryui-editable.css"}

	<script type="text/javascript">
		$(function () {
			var numberOfPictures = {$pictures|@count};
			var numberOfPicturesAllowed = {$numberOfPicturesAllowed};

			refreshUploadPictureControlState()

			ajaxifyDeleteControls();
			makeCaptionsEditable();
			makePicturesSortable();

            var spinner = $('img.imgLoading');
            spinner.hide();

			var $uploadPictureForm = $('.addPictureForm input:file');

			$uploadPictureForm
					.fileupload({
						formData: {
							listing_sid: {$listing.id},
							action: 'upload'
						},
						forceIframeTransport: true
					})
					.bind('fileuploadstart', function (e, data) {
						$(".addPictureForm .error").html("");
						$uploadPictureForm.addClass("uploading");
                        spinner.show();
					})
					.bind('fileuploaddone', function (e, data) {
						var response = JSON.parse($('pre', data.result).text())
						if (response.error) {
							$(".addPictureForm .error").html(response.error);
						}
						response.pictureInfo.forEach(function($imageLi){
							$imageLi = getDisplayImageElement($imageLi);
							$imageLi.hide();
							$('.addPictureForm').before($imageLi);
							$imageLi.slideDown(300);

							makeCaptionsEditable($imageLi);
							ajaxifyDeleteControls($imageLi);
							increaseNumberOfPictures();
						});
						$uploadPictureForm.removeClass("uploading");
                        spinner.hide();
					})

			function getDisplayImageElement(pictureInfo) {

				var liElementInnerHtml =
				'<div class="picture">' +
				'</div>' +
				'<div class="controls">' +
					'<div class="caption">' +
						'<a href="#" data-pk=""></a>' +
					'</div>' +
					'<div class="deleteWrapper">' +
						'<a title="[[Delete:raw]]" class="itemControls delete" href="">' +
							'<img src="{url file="main^icons/bin.png"}" alt="[[Delete:raw]]"/>' +
						'</a>' +
					'</div>' +
				'</div>';
                console.log(pictureInfo);
				var $imageLi = $("<li>").html(liElementInnerHtml).prop('id', 'picture_' + pictureInfo.sid);
                $(".picture", $imageLi).append($("<img>").prop('src', pictureInfo.file.picture.url));
				$(".controls .caption a", $imageLi).attr('data-pk', pictureInfo.id);
				$(".itemControls.delete", $imageLi).prop('href', "{page_path module='classifieds' function='picture_actions'}?action=delete&listing_sid={$listing.id}&picture_sid=" + pictureInfo.id);
				return $imageLi;
			}

			function decreaseNumberOfPictures() {
				numberOfPictures--;
				refreshUploadPictureControlState();
			}

			function increaseNumberOfPictures() {
				numberOfPictures++;
				refreshUploadPictureControlState();
			}

			function refreshUploadPictureControlState() {
				$("li.addPictureForm").toggle(numberOfPicturesAllowed > numberOfPictures);
			}

			function makeCaptionsEditable(context) {
				context = context || "ul.pictures";
				$('.controls .caption a', context)
						.editable({
							ajaxOptions: {
								type: 'get'
							},
							mode: 'inline',
                            onblur: 'submit',
							showbuttons: false,
							type: 'text',
							url: '{page_path module='classifieds' function='picture_actions'}?action=update_caption&listing_sid={$listing.id}',
							params: function (params) {
								var dataToPost = {
									picture_sid: params.pk,
									picture_caption: params.value
								}
								return dataToPost;
							},
							emptytext: '[[Add a Caption:raw]]',
							emptyclass: 'emptyCaption'
						})
						.prop('title', '[[Click to Modify:raw]]');
			}

			function ajaxifyDeleteControls(context) {
				context = context || "ul.pictures";
				$(".itemControls.delete", context).click(function () {
					var $parentLi = $(this).parents('li');
					$.ajax({
						type: 'get',
						url: $(this).prop("href"),
						beforeSend: function () {
							$parentLi.animate({
								backgroundColor: '#dddddd'
							}, 300);
						},
						success: function () {
							$parentLi.slideUp(300, function () {
								$parentLi.remove();
							});
						}
					});
					decreaseNumberOfPictures();
					return false;
				});
			}

			function makePicturesSortable() {
				$('ul.pictures')
						.sortable({
							items: "li:not(.addPictureForm)"
						})
						.on('sortupdate', function (event, ui) {
							var order = $(this).sortable('serialize');
							$.get('{page_path module='classifieds' function='picture_actions'}?action=change_order&listing_sid={$listing.id}&' + order);
						});
			}
		});
	</script>
</div>
