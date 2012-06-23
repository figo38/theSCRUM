-- MySQL dump 10.13  Distrib 5.1.34, for redhat-linux-gnu (i686)
--
-- Host: localhost    Database: kelkooproductbacklog
-- ------------------------------------------------------
-- Server version	5.1.34

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `sprint_days`
--

DROP TABLE IF EXISTS `sprint_days`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sprint_days` (
  `spr_id` INTEGER UNSIGNED NOT NULL,
  `spd_date` CHAR(8) NOT NULL,
  PRIMARY KEY (`spr_id`, `spd_date`),
  CONSTRAINT `FK_sprint_days_1` FOREIGN KEY `FK_sprint_days_1` (`spr_id`)
    REFERENCES `sprint` (`spr_id`)
    ON DELETE CASCADE
    ON UPDATE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `featuregroup`
--

DROP TABLE IF EXISTS `featuregroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `featuregroup` (
  `fea_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fea_name` varchar(50) CHARACTER SET latin1 NOT NULL,
  `fea_update_date` datetime DEFAULT NULL,
  `fea_create_date` datetime DEFAULT NULL,
  `usr_login` varchar(40) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`fea_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project` (
  `pro_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pro_name` varchar(50) CHARACTER SET latin1 NOT NULL,
  `usr_login` varchar(40) CHARACTER SET latin1 DEFAULT NULL,
  `pro_update_date` datetime DEFAULT NULL,
  `pro_create_date` datetime DEFAULT NULL,
  `pro_velocity` decimal(3,1) unsigned NOT NULL,
  `pro_goal` text CHARACTER SET latin1,
  `pro_has_sprints` int(1) unsigned NOT NULL DEFAULT '0',
  `pro_unit` char(1) CHARACTER SET latin1 NOT NULL DEFAULT 'H',
  `pro_generation_hour` CHAR(5) NOT NULL DEFAULT '23:00',
  PRIMARY KEY (`pro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `project_user`
--

DROP TABLE IF EXISTS `project_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_user` (
  `pro_id` int(10) unsigned NOT NULL,
  `usr_login` varchar(40) CHARACTER SET latin1 NOT NULL,
  `usr_role` char(1) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`pro_id`,`usr_login`),
  KEY `FK_project_user_user` (`usr_login`),
  CONSTRAINT `FK_project_user_project` FOREIGN KEY (`pro_id`) REFERENCES `project` (`pro_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_project_user_user` FOREIGN KEY (`usr_login`) REFERENCES `user` (`usr_login`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `releases`
--

DROP TABLE IF EXISTS `releases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `releases` (
  `rel_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rel_name` varchar(20) CHARACTER SET latin1 NOT NULL,
  `rel_type` varchar(40) CHARACTER SET latin1 DEFAULT NULL,
  `rel_planned_date` datetime DEFAULT NULL,
  `rel_deployed_date` datetime DEFAULT NULL,
  `rel_comment` text CHARACTER SET latin1,
  PRIMARY KEY (`rel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sprint`
--

DROP TABLE IF EXISTS `sprint`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sprint` (
  `spr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `spr_nb` int(10) unsigned NOT NULL,
  `spr_start_date` datetime DEFAULT NULL,
  `spr_end_date` datetime DEFAULT NULL,
  `spr_goal` text CHARACTER SET latin1,
  `pro_id` int(10) unsigned NOT NULL,
  `spr_nr_days` int(10) unsigned NOT NULL DEFAULT '0',
  `spr_nr_hours_per_day` int(10) unsigned NOT NULL DEFAULT '0',
  `spr_closed` int(1) unsigned NOT NULL DEFAULT '0',
  `spr_unit` char(1) CHARACTER SET latin1 NOT NULL DEFAULT 'H',
  `spr_copied_from_previous` char(1) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `spr_retro_1` text CHARACTER SET latin1,
  `spr_retro_2` text CHARACTER SET latin1,
  `spr_configured` INT(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`spr_id`),
  KEY `FK_sprint_project` (`pro_id`),
  CONSTRAINT `FK_sprint_project` FOREIGN KEY (`pro_id`) REFERENCES `project` (`pro_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sprint_snapshot`
--

DROP TABLE IF EXISTS `sprint_snapshot`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sprint_snapshot` (
  `sps_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `spr_id` int(10) unsigned NOT NULL,
  `sps_snapshot_date` DATETIME NOT NULL,
  `sps_tasks_nr` int(10) unsigned NOT NULL,
  `sps_tasks_todo` int(10) unsigned NOT NULL,
  `sps_tasks_inprogress` int(10) unsigned NOT NULL,
  `sps_tasks_done` int(10) unsigned NOT NULL,
  `sps_unit_reestim` int(10) unsigned NOT NULL,
  `sps_unit_remaining` int(10) unsigned NOT NULL,
  PRIMARY KEY (`sps_id`),
  KEY `FK_sprint_snapshot_1` (`spr_id`),
  CONSTRAINT `FK_sprint_snapshot_1` FOREIGN KEY (`spr_id`) REFERENCES `sprint` (`spr_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sprint_user`
--

DROP TABLE IF EXISTS `sprint_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sprint_user` (
  `spr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usr_login` varchar(40) CHARACTER SET latin1 NOT NULL,
  `spu_percentage` int(10) unsigned NOT NULL,
  PRIMARY KEY (`spr_id`,`usr_login`),
  KEY `FK_sprint_user_user` (`usr_login`),
  CONSTRAINT `FK_sprint_user_sprint` FOREIGN KEY (`spr_id`) REFERENCES `sprint` (`spr_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_sprint_user_user` FOREIGN KEY (`usr_login`) REFERENCES `user` (`usr_login`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `story`
--

DROP TABLE IF EXISTS `story`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `story` (
  `sto_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sto_prio` int(10) unsigned NOT NULL DEFAULT '0',
  `sto_estim` int(10) unsigned NOT NULL DEFAULT '0',
  `sto_percentage` int(10) unsigned NOT NULL DEFAULT '0',
  `sto_story` text CHARACTER SET latin1 NOT NULL,
  `sto_acceptance` text CHARACTER SET latin1 NOT NULL,
  `pro_id` int(10) unsigned NOT NULL,
  `sto_type` int(1) NOT NULL DEFAULT '1',
  `epi_id` int(1) unsigned DEFAULT NULL,
  `sto_create_date` datetime DEFAULT NULL,
  `sto_update_date` datetime DEFAULT NULL,
  `usr_login` varchar(40) CHARACTER SET latin1 DEFAULT NULL,
  `sto_comment` text CHARACTER SET latin1,
  `rel_id` int(10) unsigned DEFAULT NULL,
  `sto_title` text CHARACTER SET latin1,
  `is_roadmap_displayed` tinyint(1) NOT NULL DEFAULT '0',
  `sto_url` VARCHAR(200) DEFAULT NULL,
  PRIMARY KEY (`sto_id`),
  KEY `epicididx` (`epi_id`),
  KEY `proididx` (`pro_id`),
  KEY `stopcidx` (`sto_percentage`),
  KEY `FK_story_release` (`rel_id`),
  CONSTRAINT `FK_story_project` FOREIGN KEY (`pro_id`) REFERENCES `project` (`pro_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_story_release` FOREIGN KEY (`rel_id`) REFERENCES `releases` (`rel_id`) ON DELETE SET NULL,
  CONSTRAINT `FK_story_story` FOREIGN KEY (`epi_id`) REFERENCES `story` (`sto_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `story_featuregroup`
--

DROP TABLE IF EXISTS `story_featuregroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `story_featuregroup` (
  `sto_id` int(10) unsigned NOT NULL,
  `fea_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`sto_id`,`fea_id`),
  KEY `FK_story_featuregroup_featuregroup` (`fea_id`),
  CONSTRAINT `FK_story_featuregroup_featuregroup` FOREIGN KEY (`fea_id`) REFERENCES `featuregroup` (`fea_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_story_featuregroup_story` FOREIGN KEY (`sto_id`) REFERENCES `story` (`sto_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `task`
--

DROP TABLE IF EXISTS `task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `task` (
  `tsk_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tsk_title` text CHARACTER SET latin1 NOT NULL,
  `spr_id` int(10) unsigned NOT NULL,
  `sto_id` int(10) unsigned NOT NULL,
  `usr_login` varchar(40) CHARACTER SET latin1 DEFAULT NULL,
  `tsk_estim` int(10) unsigned NOT NULL DEFAULT '0',
  `tsk_reestim` int(10) unsigned NOT NULL DEFAULT '0',
  `tsk_worked` int(10) unsigned NOT NULL DEFAULT '0',
  `tsk_status` int(10) unsigned NOT NULL DEFAULT '0',
  `tsk_prio` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tsk_id`),
  KEY `FK_task_sprint` (`spr_id`),
  KEY `FK_task_story` (`sto_id`),
  CONSTRAINT `FK_task_sprint` FOREIGN KEY (`spr_id`) REFERENCES `sprint` (`spr_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_task_story` FOREIGN KEY (`sto_id`) REFERENCES `story` (`sto_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `usr_login` varchar(40) CHARACTER SET latin1 NOT NULL,
  `usr_is_admin` int(1) unsigned DEFAULT NULL,
  `usr_last_login_date` TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (`usr_login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-10-30 12:52:20


INSERT INTO user (usr_login, usr_is_admin, usr_last_login_date) VALUES ('admin', 1, NOW());