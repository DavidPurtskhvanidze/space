<script type="text/javascript">
    $(document).ready(function () {

        $(".tabs .step").hide();

        $("ul.editListing.nav a").click(function () {
            console.log(window.location.hash)
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

{display_success_messages}

<div class="listingFormContainer">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="thumbnail listingFormContainer">
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


                <div class="alert alert-warning text-center">
                    <small>
                        [[Fields marked with an asterisk (<span class="asterisk">*</span>) are mandatory]]
                    </small>
                </div>

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
                            <input type="submit" value="[[Save:raw]]" class="button btn btn-primary"/>
                        </div>
                    </div>

                </form>
            </div>
            <script type="text/javascript" src="{url file="field_types^showInputError.js"}"></script>
        </div>
    </div>


</div>

