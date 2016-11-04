{*<script type="text/javascript">*}
    {*$(document).ready(function () {*}

        {*$(".tabs .step").hide();*}

        {*$("ul.editListing.nav a").click(function () {*}
            {*console.log(window.location.hash)*}
            {*var linkHref = $(this).attr("href");*}
            {*var stepNumber = $(this).data('stepNumber');*}

            {*$('.stepsWrap .badge').removeClass('active');*}
            {*$('.stepsWrap .step' + stepNumber).addClass('active');*}

            {*$(".tabs .step").hide();*}
            {*$(".tabs " + linkHref).show();*}

            {*$("ul.editListing.nav a").closest("li").removeClass("active");*}
            {*$(this).closest("li").addClass("active");*}
            {*window.location.hash = linkHref;*}
            {*return false;*}
        {*});*}

        {*if (window.location.hash.length > 1)*}
        {*{*}
            {*$('ul.editListing.nav a[href="' +  window.location.hash + '"]').click();*}
        {*}*}
        {*else*}
        {*{*}
            {*$("ul.editListing.nav a:first").click();*}
        {*}*}
    {*});*}
{*</script>*}



<div class="row">
    <div class="col-md-10 col-md-offset-1">
        {display_success_messages}
        <div class="stepsWrap steps-wrap">
            {*<ul class="editListing nav nav-tabs nav-justified" role="tablist">*}
            {*{strip}*}
            {*{foreach $steps as $stepNumber=>$step}*}
            {*<li>*}
            {*<div class="visible-lg-block text-center stepLine {if $step@first}first{/if}{if $step@last} last{/if}">*}
            {*<span class="badge step{$stepNumber}">{$step@iteration}</span>*}
            {*{if $step@first}<div class="firstStep"></div>{/if}*}
            {*{if $step@last}<div class="lastStep"></div>{/if}*}
            {*</div>*}
            {*<a href="#step{$stepNumber}" data-step-number="{$stepNumber}">[[$step.title]]</a>*}
            {*</li>*}
            {*{/foreach}*}
            {*{/strip}*}
            {*</ul>*}


            <!-- Nav tabs -->
            <ul class="list-unstyled" role="tablist">
                {strip}
                    {foreach $steps as $stepNumber=>$step}
                        <li role="presentation" class="{if $step@first}active{/if}">

                            <span class="add-listing-step-title">
                                <a href="#step{$stepNumber}" aria-controls="step{$stepNumber}" role="tab" data-toggle="tab">[[$step.title]]</a>
                            </span>
                            <span class="add-listing-step"></span>
                            {if !$step@first}
                                <span class="add-listing-step-line"></span>
                            {/if}
                        </li>
                    {/foreach}
                {/strip}
            </ul>
        </div>


        <div class="alert alert-warning text-center">
            [[Fields marked with an asterisk (<span class="asterisk">*</span>) are mandatory]]
        </div>



        <form method="post" action="" enctype="multipart/form-data" class="form-horizontal">
            {CSRF_token}
            <div>
                {*<div class="tabs">*}
                {*{$formContent}*}
                {*</div>*}
                <!-- Tab panes -->
                <div id="edit-listing-fields-container" class="tab-content">
                    {$formContent}
                </div>
                <div  class="clearfix"></div>
                <div class="formConrols text-center">
                    <input type="hidden" name="action" value="save_info"/>
                    <input type="hidden" name="listing_id" value="{$listing.id}"/>
                    <button type="submit" class="default-button wb" value="1">[[Save]]</button>
                </div>
            </div>
            <script type="text/javascript">
                $('#edit-listing-fields-container .step').addClass('tab-pane').attr('role','tabpanel');
                $('#edit-listing-fields-container #step1').addClass('active');
            </script>

        </form>
        <script type="text/javascript" src="{url file="field_types^showInputError.js"}"></script>
    </div>
</div>

