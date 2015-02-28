-- 28/02/2015

ALTER TABLE `kalender`
	ADD COLUMN `IsTraining` BIT(1) NOT NULL DEFAULT b'0';

CREATE TABLE `training` (
	`Id` INT NOT NULL AUTO_INCREMENT,
	`KalenderId` INT NOT NULL DEFAULT '0',
	`SpelerId` INT NOT NULL DEFAULT '0'
)
COMMENT='kalender heeft IsTraining voor inschrijvingen geleide trainingen';

alter table `training` add column `Uur` int;

INSERT INTO `ttc_erembodegem`.`parameter` (`sleutel`, `value`, `omschrijving`) VALUES ('training_personen', '8', 'Aantal personen dat kan meedoen aan geleide trainingen');