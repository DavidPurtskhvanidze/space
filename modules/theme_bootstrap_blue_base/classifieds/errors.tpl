<div class="container">
    {foreach from=$ERRORS item="error_message" key="error"}
        {if is_int($error)}{assign var="error" value=$error_message}{/if}
        {if $error eq "INVALID_REQUEST"}
            <p class="error">{$error_message}</p>
        {elseif $error eq "INVALID_DATA"}
            <p class="error">{$error_message}</p>
        {elseif $error eq "REQUEST_FORM_NOT_EXIST"}
            <p class="error">[[Provided request form does not exist, please contact administrator]]</p>
        {elseif $error eq "INPUT_FORM_NOT_EXIST"}
            <p class="error">[[Provided input form does not exist, please contact administrator]]</p>
        {elseif $error eq "PARAMETERS_MISSED"}
            <p class="error">[[The system cannot proceed as some key parameters are missing]]</p>
        {elseif $error eq "MYSQL_ERROR"}
            {$error_message}
        {elseif $error eq "NOT_LOGGED_IN" or $error_message eq 'NOT_LOGGED_IN'}
            <p class="error">[[Please log in to use this page]]</p>
        {elseif $error eq 'DEFAULT_VALUE_NOT_SET'}
            <p class="error">Default value for {$error_message} is not set</p>
        {elseif $error eq 'PERIOD_FROM_IS_EMPTY' or $error_message eq 'PERIOD_FROM_IS_EMPTY'}
            <p class="error">[[The beginning of the period is not specified (empty)]]</p>
        {elseif $error eq 'PERIOD_TO_IS_EMPTY' or $error_message eq 'PERIOD_TO_IS_EMPTY'}
            <p class="error">[[The end of the period is not specified (empty)]]</p>
        {elseif $error eq 'LISTING_SID_IS_EMPTY' or $error_message eq 'LISTING_SID_IS_EMPTY'}
            <p class="error">[[Listing ID is not specified]]</p>
        {elseif $error eq 'FIELD_SID_IS_EMPTY' or $error_message eq 'FIELD_SID_IS_EMPTY'}
            <p class="error">[[Calendar ID is not specified]]</p>
        {elseif $error eq 'UNKNOWN_DATE_FORMAT_IN_PERIOD_FROM' or $error_message eq 'UNKNOWN_DATE_FORMAT_IN_PERIOD_FROM'}
            <p class="error">[[The beginning of the period contains unknown date format. Please put your date in the yyyy-mm-dd]]</p>
        {elseif $error eq 'UNKNOWN_DATE_FORMAT_IN_PERIOD_TO' or $error_message eq 'UNKNOWN_DATE_FORMAT_IN_PERIOD_TO'}
            <p class="error">[[The end of the period contains unknown date format. Please put your date in the yyyy-mm-dd]]</p>
        {elseif $error eq 'LISTING_NOT_FOUND' or $error_message eq 'LISTING_NOT_FOUND'}
            <p class="error">[[Listing was not found]]</p>
        {elseif $error eq 'FIELD_NOT_FOUND' or $error_message eq 'FIELD_NOT_FOUND'}
            <p class="error">[[Calendar was not found]]</p>
        {elseif $error eq 'PERIODS_INTERSECTS' or $error_message eq 'PERIODS_INTERSECTS'}
            <p class="error">[[This listing is not available for some time within the requested booking period. Please choose another period that does not overlap with the existing booking(s) marked on the calendar.]]</p>
        {elseif $error eq 'FROM_MUST_BE_BEFORE_TO' or $error_message eq 'FROM_MUST_BE_BEFORE_TO'}
            <p class="error">[[Start date of the period exceed the end date]]</p>
        {elseif $error eq 'AUTHORIZATION_FAILED' or $error_message eq 'AUTHORIZATION_FAILED'}
            <p class="error">[[You have no rights to create the period]]</p>
        {elseif $error eq 'DELETE_AUTHORIZATION_FAILED' or $error_message eq 'DELETE_AUTHORIZATION_FAILED'}
            <p class="error">[[You have no rights to remove period]]</p>
        {elseif $error eq 'PERIOD_NOT_EXISTS' or $error_message eq 'PERIOD_NOT_EXISTS'}
            <p class="error">[[Removed period does not exist]]</p>
        {elseif $error eq 'EMAIL_IS_EMPTY' or $error_message eq 'EMAIL_IS_EMPTY'}
            <p class="error">[[The email field is empty. Please type in your email address]]</p>
        {elseif $error eq 'EMAIL_NOT_VALID' or $error_message eq 'EMAIL_NOT_VALID'}
            <p class="error">[[Your email address is not properly formatted. Please type your email address in an appropriate format (yourname@email.com)]]</p>
        {elseif $error eq 'NAME_IS_EMPTY' or $error_message eq 'NAME_IS_EMPTY'}
            <p class="error">[[Your NAME field is empty]]</p>
        {elseif $error eq 'RATE_IS_NOT_VALID' or $error_message eq 'RATE_IS_NOT_VALID'}
            <p class="error">[[Your rating value is invalid. Value must be between 1 and 5]]</p>
        {elseif $error eq 'INCORRECT_SECURITY_CODE'}
            <p class="error">[[Your rating value is invalid. Value must be between 1 and 5]]</p>
        {elseif $error eq 'CANNOT_SEND_MAIL'}
            <p class="error">[[Unable to send mail. If this error persists please contact site administrator.]]</p>
        {elseif $error eq 'CANNOT_SEND_MAIL_CHECK_EMAIL'}
            <p class="error">[[Unable to send mail. Please make sure you typed the email correctly. If this error persists please contact site administrator.]]</p>
        {elseif $error eq 'NOT_OWNER_OF_LISTING'}
            <p class="error">[[You are not the owner of the listing]]</p>
        {elseif $error eq 'OBJECT_SID_IS_EMPTY'}
            <p class="error">[[Object ID is not specified]]</p>
        {elseif $error eq 'OBJECT_TYPE_IS_EMPTY'}
            <p class="error">[[Object type is not specified]]</p>
        {elseif $error eq 'SEARCH_EXPIRED'}
            <p class="error">[[Unfortunately, your search criteria have expired. Please start the search over.]]</p>
        {elseif $error eq 'BROWSING_ERROR_INVALID_FIELD_ID'}
            <p class="error">[[Invalid field "$error_message" was specified either in the template which calls browsing function, or in the browsing Site Page settings.]]</p>
        {else}
            <p class="error">[[$error]]</p>
        {/if}
    {/foreach}
</div>
