<script type="text/javascript">
    $(document).ready(function () {

        $(".tabs .step").hide();

        $("ul.editListing.nav a").click(function () {
            var linkHref = $(this).attr("href");
            var stepNumber = $(this).data('stepNumber');

            $('.stepsWrap .badge').removeClass('active');
            $('.stepsWrap .step' + stepNumber).addClass('active');

            $(".tabs .step").hide();
            $(".tabs " + linkHref).show();

            $("ul.editListing.nav a").closest("li").removeClass("active");
            $(this).closest("li").addClass("active");
            window.location.hash = linkHref;
            return false;
        });
        if (window.location.hash.length > 1)
        {
            $('ul.editListing.nav a[href="' +  window.location.hash + '"]').click();
        }
        else
        {
            $("ul.editListing.nav a:first").click();
        }
    });
</script>

<div class="listingFormContainer">
    <div class="container">
        <div class="stepsWrap text-center">
            <ul class="editListing nav nav-tabs nav-justified" role="tablist">
                {strip}
                    {foreach $steps as $stepNumber=>$step}
                        <li>
                            <div class="visible-lg-block text-center stepLine {if $step@first}first{/if}{if $step@last} last{/if}">
                                <span class="badge step{$stepNumber}">{$step@iteration}</span>
                                {if $step@first}<div class="firstStep"></div>{/if}
                                {if $step@last}<div class="lastStep"></div>{/if}
                            </div>
                            <a href="#step{$stepNumber}" data-step-number="{$stepNumber}">[[$step.title]]</a>
                        </li>
                    {/foreach}
                {/strip}
            </ul>
        </div>
        <div class="space-20"></div>
        <div class="space-20"></div>
        <div class="space-20"></div>
        <strong>
            [[FormFieldCaptions!Category]]:&nbsp;&nbsp;
        </strong>
        {foreach from=$ancestors item=ancestor name="ancestors_cycle"}
            [[$ancestor.caption]]&nbsp;&nbsp;<i class="fa fa-caret-right"></i>&nbsp;&nbsp;
        {/foreach}
        <div class="space-20"></div>
        {display_success_messages}
        <div class="space-20"></div>
        <div class="bg-info text-center">
            [[Fields marked with an asterisk (<span class="asterisk">*</span>) are mandatory]]
        </div>

    </div>
    <div class="space-20"></div>
    <div class="bg-grey">
        <div class="space-20"></div>
        <div class="space-20"></div>
        <div class="container">
            <form method="post" action="" enctype="multipart/form-data" class="form-horizontal">
                {CSRF_token}
                <div>
                    <div class="tabs">
                        {$formContent}
                    </div>
                    <div  class="clearfix"></div>
                    <div class="formConrols text-center">
                        <input type="hidden" name="action" value="save_info"/>
                        <input type="hidden" name="listing_id" value="{$listing.id}"/>
                        <input type="submit" value="[[Save:raw]]" class="btn btn-orange h5"/>
                    </div>
                </div>
           </form>
        </div>
        <div class="space-20"></div>
        <div class="space-20"></div>
        <div class="space-20"></div>
    </div>
    <script type="text/javascript" src="{url file="field_types^showInputError.js"}"></script>
</div>

