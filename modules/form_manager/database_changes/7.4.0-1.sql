ALTER TABLE  `form_manager_forms` ADD  `application_id` VARCHAR( 100 ) NOT NULL DEFAULT 'FrontEnd',
ADD INDEX (  `application_id` ) ;
