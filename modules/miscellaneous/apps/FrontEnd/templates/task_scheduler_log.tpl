{$smarty.now|date_format:"%Y-%m-%d %T"}:
 * Expired Listings: {foreach from=$expired_listings_id item=listing_id} {$listing_id}{foreachelse}none{/foreach} 
 * Notified Saved Searches: {foreach from=$notified_saved_searches_id item=search_id name=searches} {$search_id}{foreachelse}none{/foreach} 
 * Expired Contracts: {foreach from=$expired_contracts_id item=contract_id name=expired_contracts} {$contract_id}{foreachelse}none{/foreach} 
