{include file="user_details.tpl"}
{module name="classifieds" function="search_results" action="search" QUERY_STRING="username[equal]="|cat:$user.username}
