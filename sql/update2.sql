ALTER TABLE `visit` ADD `participation_id` INT NULL AFTER `competition_id`;

ALTER TABLE `competition` ADD `open_time` INT NULL AFTER `activity_id`, ADD `close_time` INT NULL AFTER `open_time`;

ALTER TABLE `competition` ADD UNIQUE(`code`);

ALTER TABLE `place` ADD UNIQUE(`code`);
