-- 28/02/2015 - Geleide trainingen

CREATE TABLE `training` (
	`Id` INT NOT NULL AUTO_INCREMENT,
	`KalenderId` INT NOT NULL DEFAULT '0',
	`SpelerId` INT NOT NULL DEFAULT '0'
)
COMMENT='kalender heeft GeleideTraining voor inschrijvingen geleide trainingen';

alter table `training` add column `Uur` int;

INSERT INTO `ttc_erembodegem`.`parameter` (`sleutel`, `value`, `omschrijving`) VALUES ('training_personen', '8', 'Aantal personen dat kan meedoen aan geleide trainingen');

ALTER TABLE `kalender`
	ADD COLUMN `GeleideTraining` VARCHAR(150) NULL DEFAULT NULL ;

INSERT INTO `ttc_erembodegem`.`parameter` (`sleutel`, `value`) VALUES ('training_kaldesc', '20,21,Geleide train');
UPDATE `ttc_erembodegem`.`parameter` SET `value`='21,22,Geleide training om 21u ({vrij1} plaatsen vrij) en om 22u ({vrij2} plaatsen vrij)',
`omschrijving`='Default value voor geleide training in de admin sectie' WHERE  `sleutel`='training_kaldesc';