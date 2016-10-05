{assign var="categoryId" value=$destination_category.id}
{assign var="destinationCategorySid" value=$GLOBALS.site_url|cat:"/edit_category/?sid="|cat:$destination_category.sid}
[[Category successfully relocated to <a href="$destinationCategorySid">$categoryId</a>.]]
