INSERT INTO `email_templates` (`id`, `subject`, `body`, `last_modified`) VALUES
('user_change_password', '{page_url id=''root'' app=''FrontEnd''}: Request to Change User Password', '<p>Dear {$user.username},<br />\r\n<br />\r\nSomeone, either you or someone else, submitted a request to change your user password. If you did not place the request yourself, simply disregard this message. If you submitted the request, please go ahead and change your password by following the link below:<br />\r\n<a href="{page_url module=''users'' function=''change_password'' app=''FrontEnd''}?username={$user.username}&amp;verification_key={$user.verification_key}">Change your password</a><br />\r\n<br />\r\nBest regards,<br />\r\nAdministrator, {page_url id=''root'' app=''FrontEnd''}</p>\r\n', '2015-05-22 15:04:19'),
('activate_account', 'New Account Activation On {page_url id=''root'' app=''FrontEnd''}', '<pre>\r\nHello!\r\n<span style="color:#e8bf6a">\r\n</span>You have created a new account, <em>"{$user.username}"</em>, on <a href="{page_url id=''root'' app=''FrontEnd''}">{page_url id=''root'' app=''FrontEnd''}</a>.\r\n<span style="color:#e8bf6a">\r\n</span>To activate your account, please click the link below:<span style="color:#e8bf6a">\r\n</span><a href="{page_url module=''users'' function=''activate_account'' app=''FrontEnd''}?username={$user.username}&amp;activation_key={$user.activation_key}">Activate your account</a><span style="color:#e8bf6a">\r\n</span>\r\nThank you and welcome aboard, <span style="color:#e8bf6a">\r\n</span>\r\n</pre>\r\n\r\n<pre>\r\n<a href="{page_url id=''root'' app=''FrontEnd''}">{page_url id=''root'' app=''FrontEnd''}</a>  <span style="color:rgb(232, 191, 106); font-family:sans-serif,arial,verdana,trebuchet ms"> </span><span style="font-family:sans-serif,arial,verdana,trebuchet ms">Administrator</span></pre>\r\n', '2015-05-22 14:50:27');

UPDATE `site_pages_pages` SET `id` = 'users'  WHERE `uri` = '/users/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'edit_user' WHERE `uri` = '/edit_user/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'user_groups' WHERE `uri` = '/user_groups/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'add_user_group' WHERE `uri` = '/add_user_group/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'edit_user_group' WHERE `uri` = '/edit_user_group/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'delete_user_group' WHERE `uri` = '/delete_user_group/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'user_profile_fields' WHERE `uri` = '/user_profile_fields/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'add_user_profile_field' WHERE `uri` = '/add_user_profile_field/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'edit_user_profile' WHERE `uri` = '/edit_user_profile/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'delete_user_profile_field' WHERE `uri` = '/delete_user_profile_field/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'edit_user_profile_field_edit_list' WHERE `uri` = '/edit_user_profile_field/edit_list/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'edit_user_profile_field_edit_list_item' WHERE `uri` = '/edit_user_profile_field/edit_list_item/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'edit_user_profile_field' WHERE `uri` = '/edit_user_profile_field/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'edit_user_profile_field_edit_tree' WHERE `uri` = '/edit_user_profile_field/edit_tree/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'edit_user_profile_field_import_tree_data' WHERE `uri` = '/edit_user_profile_field/import_tree_data/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'users', `template` = 'user_details.tpl' WHERE `uri` = '/users/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'user_login' WHERE `uri` = '/user/login/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'user_registration' WHERE `uri` = '/user/registration/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'user_logout' WHERE `uri` = '/user/logout/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'password_recovery' WHERE `uri` = '/password-recovery/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'user_profile', `template` = 'user_profile' WHERE `uri` = '/user/profile/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'users_search' WHERE `uri` = '/users/search/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'users_listings', `template` = 'search.tpl' WHERE `uri` = '/users/listings/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'users_contact' WHERE `uri` = '/users/contact/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'user_notifications' WHERE `uri` = '/user/notifications/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'users' WHERE `uri` = '/users/' AND `application_id` = 'MobileFrontEnd';
UPDATE `site_pages_pages` SET `id` = 'users_search' WHERE `uri` = '/users/search/' AND `application_id` = 'MobileFrontEnd';
UPDATE `site_pages_pages` SET `id` = 'users_contact' WHERE `uri` = '/users/contact/' AND `application_id` = 'MobileFrontEnd';
UPDATE `site_pages_pages` SET `id` = 'users_listings' WHERE `uri` = '/users/listings/' AND `application_id` = 'MobileFrontEnd';
UPDATE `site_pages_pages` SET `id` = 'user_login' WHERE `uri` = '/user/login/' AND `application_id` = 'MobileFrontEnd';
UPDATE `site_pages_pages` SET `id` = 'user_logout' WHERE `uri` = '/user/logout/' AND `application_id` = 'MobileFrontEnd';
