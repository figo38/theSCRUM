ALTER TABLE `project` DROP COLUMN `pro_sprint_by_quarter`;

ALTER TABLE `story` ADD COLUMN `sto_url` VARCHAR(200) DEFAULT NULL;
