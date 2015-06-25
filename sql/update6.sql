ALTER TABLE `image` 
ADD `name` VARCHAR(255) NULL AFTER `location`, 
ADD `description` TEXT NULL AFTER `name`;