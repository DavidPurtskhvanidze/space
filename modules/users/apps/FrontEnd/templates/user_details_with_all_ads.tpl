{include file="user_details.tpl"}
<div class="clearBoth"></div>
{module name="classifieds" function="search_results"  QUERY_STRING="username[equal]="|cat:$user.username|cat:""}
