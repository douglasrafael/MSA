-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema msa
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `msa` ;

-- -----------------------------------------------------
-- Schema msa
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `msa` DEFAULT CHARACTER SET utf8 ;
USE `msa` ;

-- -----------------------------------------------------
-- Table `msa`.`aluno`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `msa`.`aluno` ;

CREATE TABLE IF NOT EXISTS `msa`.`aluno` (
  `matricula` INT NOT NULL,
  `nome` VARCHAR(245) NOT NULL,
  `dataNascimento` DATE NULL,
  `dataCadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`matricula`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `msa`.`disciplina`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `msa`.`disciplina` ;

CREATE TABLE IF NOT EXISTS `msa`.`disciplina` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(145) NOT NULL,
  `cargaHoraria` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nome_UNIQUE` (`nome` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `msa`.`atividade`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `msa`.`atividade` ;

CREATE TABLE IF NOT EXISTS `msa`.`atividade` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(245) NOT NULL,
  `descricao` TEXT NULL,
  `dataEntrega` DATE NOT NULL,
  `dataCadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `aluno_matricula` INT NOT NULL,
  `disciplina_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_atividade_aluno1_idx` (`aluno_matricula` ASC),
  INDEX `fk_atividade_disciplina1_idx` (`disciplina_id` ASC),
  CONSTRAINT `fk_atividade_aluno1`
    FOREIGN KEY (`aluno_matricula`)
    REFERENCES `msa`.`aluno` (`matricula`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_atividade_disciplina1`
    FOREIGN KEY (`disciplina_id`)
    REFERENCES `msa`.`disciplina` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `msa`.`documento`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `msa`.`documento` ;

CREATE TABLE IF NOT EXISTS `msa`.`documento` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(245) NOT NULL,
  `endereco` VARCHAR(245) NOT NULL,
  `atividade_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_documento_atividade1_idx` (`atividade_id` ASC),
  CONSTRAINT `fk_documento_atividade1`
    FOREIGN KEY (`atividade_id`)
    REFERENCES `msa`.`atividade` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
