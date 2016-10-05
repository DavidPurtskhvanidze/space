<div class="registrationPage">
    <h1 class="page-title">[[Registration]]</h1>
    {display_error_messages}
    <p class="alert bg-info">[[Fields marked with an asterisk (<span class="asterisk">*</span>) are mandatory]]</p>

    <form method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
        {if $form_fields|@count > 10}
            {assign var=parts value=2}
        {else}
            {assign var=parts value=1}
        {/if}

        {assign var=normalized value=false}
        {math equation="ceil(values / parts)" assign=part_items values=$form_fields|@count parts=$parts}
        {section name=part loop=$parts}
            {assign var=start value=$part_items*$smarty.section.part.index}
            {assign var=form_fields_part value=$form_fields|@array_slice:$start:$part_items}
            {assign var=parts_parts value=2}
            {math equation="ceil(values / parts)" assign=part_part_items values=$form_fields_part|@count parts=$parts_parts}
            <div class="row">
                {section name=part_part loop=$parts_parts}
                    {assign var=start value=$part_part_items*$smarty.section.part_part.index}
                    <div class="col-md-6 col-sm-8 col-sm-offset-2 col-md-offset-0">
                        {foreach from=$form_fields_part|@array_slice:$start:$part_part_items item=form_field}
                            <div class="form-group {$form_field.type}">
                                <label for="{$form_field.id}" class="col-md-3 control-label">
                                    {if $form_field.type !== 'boolean'}
                                        [[$form_field.caption]]
                                    {/if}
                                    {if $form_field.is_required}<span class="asterisk">*</span>{/if}
                                </label>

                                <div class="col-md-9">
                                    {input property=$form_field.id}
                                </div>
                            </div>
                        {/foreach}
                    </div>
                {/section}
            </div>
            {if !$smarty.section.part.last}
                <hr/>
                <div class="space-20"></div>
            {/if}
        {/section}
        <div class="space-20"></div>
        <div class="form-group text-center">
            <div class="col-xs-12">
                <input type="hidden" name="action" value="register"/>
                <input type="hidden" name="user_group_id" value="{$user_group_id}"/>

                <button type="submit" class="btn btn-orange btn1">[[Register:raw]]</button>
            </div>
        </div>
    </form>

    {require component="jquery" file="jquery.js"}
    {require component="jquery-maxlength" file="jquery.maxlength.js"}
    {require component="js" file="script.maxlength.js"}
    <script type="text/javascript" src="{url file="field_types^showInputError.js"}"></script>

</div>
