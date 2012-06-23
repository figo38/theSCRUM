ALTER TABLE `user` MODIFY COLUMN `usr_last_login_date` TIMESTAMP NOT NULL;

ALTER TABLE `sprint` ADD COLUMN `spr_configured` INT(1) UNSIGNED NOT NULL DEFAULT 0;

ALTER TABLE `project` ADD COLUMN `pro_generation_hour` CHAR(5) NOT NULL DEFAULT '23:00';

CREATE TABLE `sprint_days` (
  `spr_id` INTEGER UNSIGNED NOT NULL,
  `spd_date` CHAR(8) NOT NULL,
  PRIMARY KEY (`spr_id`, `spd_date`),
  CONSTRAINT `FK_sprint_days_1` FOREIGN KEY `FK_sprint_days_1` (`spr_id`)
    REFERENCES `sprint` (`spr_id`)
    ON DELETE CASCADE
    ON UPDATE RESTRICT
) ENGINE = InnoDB;

UPDATE sprint SET spr_configured=1, spr_copied_from_previous=1 WHERE spr_closed=1;

ALTER TABLE `sprint_snapshot` MODIFY COLUMN `sps_snapshot_date` DATETIME NOT NULL;
