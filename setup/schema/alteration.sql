ALTER TABLE `appdevrequests` ADD `durationUnit` ENUM('d','w','m') NOT NULL AFTER `duration`;
ALTER TABLE `appdevrequests` ADD `budgetUnit` ENUM('d','n') NOT NULL AFTER `budget`;
ALTER TABLE `bookdev` CHANGE `dateNeeded` `dateNeeded` DATETIME NOT NULL;
ALTER TABLE `trainingenrollement` CHANGE `phone` `phone` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `appdevrequests` CHANGE `phone` `phone` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `bookdev` CHANGE `phone` `phone` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;