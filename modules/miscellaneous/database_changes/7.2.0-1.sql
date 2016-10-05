INSERT INTO `core_settings` (`name`, `value`) VALUES
  ('main_logo', NULL),
  ('mobile_logo', NULL);

INSERT INTO `email_templates` (`id`, `subject`, `body`, `last_modified`) VALUES
  ('admin_contact_form_message', 'Comments From {$name} On {$GLOBALS.site_url}', '|| name: {$name}<br/>|| email: {$email}<br/>|| comments: <br />{$comments}<br/>', '2015-01-05 11:32:13'),
  ('admin_expired_contracts_and_listings_report', '{if $expiredContractsLog|@count && $expiredListingsLog|@count} {$GLOBALS.site_url}: Expired Contracts And Expired Listings Report {elseif $expiredContractsLog|@count} {$GLOBALS.site_url}: Expired Contracts Report {elseif $expiredListingsLog|@count} {$GLOBALS.site_url}: Expired Listings Report {/if}', '<p><br />\r\n{if $expiredContractsLog|@count}<br />\r\n<strong>User With Expired Contracts</strong><br />\r\n{foreach from=$expiredContractsLog item=expiredContractLog}<br />\r\n<strong>{$expiredContractLog.user.username}</strong>&nbsp;{$expiredContractLog.user.email}<br />\r\n<span style="line-height:1.6em">{/foreach}</span><br />\r\n<span style="line-height:1.6em">{/if}</span></p>\r\n\r\n<p>{if $expiredListingsLog|@count}<br />\r\n<strong>Expired Listings by Users</strong><br />\r\n{foreach from=$expiredListingsLog item=expiredUserListingsLog}<br />\r\n<strong>{$expiredUserListingsLog.username} listings</strong><br />\r\nExpired listings: {foreach from=$expiredUserListingsLog.expiredListingsSid item=listingSid}{$listingSid} {/foreach}<br />\r\n{/foreach}<br />\r\n{/if}</p>\r\n', '2015-01-05 11:35:46');

