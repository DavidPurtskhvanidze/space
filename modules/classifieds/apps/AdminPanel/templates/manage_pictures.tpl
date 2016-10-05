<div class="managePictures">
<div class="breadcrumbs">
  <ul class="breadcrumb">
	  <li><a href="{$GLOBALS.site_url}/manage_listings/?action=restore&amp;searchId={$searchId}">[[Manage Listings]]</a></li>
	  <li><a href="{page_path id='edit_listing'}?listing_id={$listing_id}&amp;searchId={$searchId}">[[Edit Listing]]</a></li>
	  <li>[[Manage Pictures]]</li>
  </ul>
</div>

  <div class="page-content">
    <div class="page-header">
      <h1>[[Manage Pictures for the Listing #$listing_id]]</h1>
    </div>

    <div class="row">
    {display_error_messages}
    {display_success_messages}
      <div class="row">
        <div class="col-sm-8">
          [[This listing is]]
          {if $listing_info.active}<span class="text-success">[[Active]]</span>{else}<span class="text-danger">[[Inactive]]</span>{/if}
          {if !empty($listing_info.moderation_status)} [[and]] <span class="{if $listing_info.moderation_status=='APPROVED'}text-success{else}text-danger{/if}">[[$listing_info.moderation_status]]</span>{/if}

          <div class="btn-group">
            {capture assign="returnBackUri"}{page_uri module='classifieds' function='manage_pictures'}?searchId={$searchId}&listing_id={$listing_id}{/capture}
            {if $listing_info.active}
              <a class="btn btn-inverse btn-xs" href="{page_path module='classifieds' function='listing_actions'}?searchId={$searchId}&amp;listings[{$listing_id}]=1&amp;action=Deactivate&amp;returnBackUri={$returnBackUri|urlencode}">[[Deactivate]]</a>
            {else}
              <a class="btn btn-inverse btn-xs" href="{page_path module='classifieds' function='listing_actions'}?searchId={$searchId}&amp;listings[{$listing_id}]=1&amp;action=Activate&amp;returnBackUri={$returnBackUri|urlencode}">[[Activate]]</a>
            {/if}
            <a class="btn btn-inverse btn-xs" href="{page_path id='display_listing'}?searchId={$searchId}&amp;listing_id={$listing_id}">[[View Listing]]</a>
          </div>
        </div>
      </div>

      <div class="space-4"></div>

      {include file='miscellaneous^field_errors.tpl' errors=$field_errors}

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
                               href="{page_path module='classifieds' function='picture_actions'}?action=delete&amp;listing_sid={$listing_id}&amp;picture_sid={$picture.id}">
                                <i class="icon-trash bigger-120"></i>
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

            refreshUploadPictureControlState()

            ajaxifyDeleteControls();
            makeCaptionsEditable();
            makePicturesSortable();

            var spinner = $('img.imgLoading');
            spinner.hide();
            var $uploadPictureForm = $('.addPictureForm input:file');
	        var $uploadPictureFormContainer = $uploadPictureForm.closest('div.fileInputButton');
	        var $uploadPictureFormContainerBgImage = $uploadPictureFormContainer.css('background-image');

            $uploadPictureForm
                    .fileupload({
                        formData: {
                            listing_sid: {$listing_id},
                            action: 'upload'
                        },
                        forceIframeTransport: true
                    })
                    .bind('fileuploadstart', function (e, data) {
                        $(".addPictureForm .error").html("");
                        $uploadPictureForm.addClass("uploading");
                        spinner.show();
			            $uploadPictureFormContainer.css('background-image', 'none');
                    })
                    .bind('fileuploaddone', function (e, data) {
                        var response = JSON.parse($('pre', data.result).text());
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
			            $uploadPictureFormContainer.css('background-image', $uploadPictureFormContainerBgImage);
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
                        '<i class="icon-trash bigger-120"></i>' +
                        '</a>' +
                        '</div>' +
                        '</div>';

                var $imageLi = $("<li>").html(liElementInnerHtml).prop('id', 'picture_' + pictureInfo.id);
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
                $("li.addPictureForm").toggle(true);
            }

            function makeCaptionsEditable(context) {
                context = context || "ul.pictures";
                $('.controls .caption a', context)
                        .editable({
                            ajaxOptions: {
                                type: 'get'
                            },
                            mode: 'inline',
                            showbuttons: false,
                            onblur: 'submit',
                            type: 'text',
                            url: '{page_path module='classifieds' function='picture_actions'}?action=update_caption&listing_sid={$listing_id}',
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
                            $.get('{page_path module='classifieds' function='picture_actions'}?action=change_order&listing_sid={$listing_id}&' + order);
                        });
            }
        });
    </script>
  </div>
</div>

<div id="errorMessageDialog" title="[[Field value error:raw]]"></div>
