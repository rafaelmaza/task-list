SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `tasklist` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `tasklist` ;

-- -----------------------------------------------------
-- Table `tasklist`.`user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tasklist`.`user` (
  `user_id` INT NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(14) NOT NULL ,
  `password` VARCHAR(32) NOT NULL ,
  `created` DATETIME NOT NULL ,
  `last_login` DATETIME NOT NULL ,
  PRIMARY KEY (`user_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tasklist`.`task`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tasklist`.`task` (
  `task_id` INT NOT NULL AUTO_INCREMENT ,
  `description` TEXT NOT NULL ,
  `priority` INT NULL COMMENT '1-Low/2-Medium/3-High' ,
  `due_date` DATE NULL ,
  `completed` TINYINT NOT NULL DEFAULT 0 COMMENT '0-Not Completed/1-Completed' ,
  `user_id` INT NOT NULL ,
  PRIMARY KEY (`task_id`) ,
  INDEX `fk_task_user` (`user_id` ASC) ,
  CONSTRAINT `fk_task_user`
    FOREIGN KEY (`user_id` )
    REFERENCES `tasklist`.`user` (`user_id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
