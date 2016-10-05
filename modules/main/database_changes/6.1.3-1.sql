ALTER TABLE  `core_administrator` ADD  `group` VARCHAR( 50 ) NOT NULL AFTER  `password`;

UPDATE `core_administrator` SET  `group` =  'admin' WHERE  `core_administrator`.`username` =  'admin';
