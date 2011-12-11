SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `utilisateur`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `utilisateur` ;

CREATE  TABLE IF NOT EXISTS `utilisateur` (
  `id_utilisateur` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(128) NOT NULL ,
  `password` CHAR(40) NOT NULL ,
  `nom` VARCHAR(64) NOT NULL ,
  `prenom` VARCHAR(64) NOT NULL ,
  `adresse` TEXT NULL ,
  `latitude` VARCHAR(20) NULL ,
  `longitude` VARCHAR(20) NULL ,
  `sexe` CHAR(1) NULL ,
  `date_naissance` CHAR(10) NULL ,
  `avatar` TEXT NULL COMMENT 'chemin vers le fichier image' ,
  `date_creation` DATETIME NOT NULL ,
  `date_maj` DATETIME NULL ,
  PRIMARY KEY (`id_utilisateur`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `groupe`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `groupe` ;

CREATE  TABLE IF NOT EXISTS `groupe` (
  `id_groupe` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nom` VARCHAR(64) NOT NULL ,
  `description` TEXT NOT NULL ,
  `avatar` TEXT NULL COMMENT 'chemin vers le fichier image' ,
  `poids_recommandation` INT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Indice calculé à partir du nombre de recommandations des groupes qui ont recommandé celui-ci' ,
  `ferme` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Indique si le groupe nécessite une validation de l\'administrateur pour le rejoindre (1), ou si les adhésions (membre) sont libres (0)' ,
  `date_creation` DATETIME NOT NULL ,
  `date_maj` DATETIME NULL ,
  `id_utilisateur` INT UNSIGNED NOT NULL COMMENT 'id utilisateur qui est administrateur' ,
  PRIMARY KEY (`id_groupe`) ,
  INDEX `fk_groupe_idutilisateur` (`id_utilisateur` ASC) ,
  CONSTRAINT `fk_groupe_idutilisateur`
    FOREIGN KEY (`id_utilisateur` )
    REFERENCES `utilisateur` (`id_utilisateur` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `adhesion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `adhesion` ;

CREATE  TABLE IF NOT EXISTS `adhesion` (
  `id_groupe` INT UNSIGNED NOT NULL ,
  `id_utilisateur` INT UNSIGNED NOT NULL ,
  `type` ENUM('membre','favoris') NOT NULL ,
  `statut` TINYINT(1) UNSIGNED NOT NULL COMMENT '0 : en attente\n1 : validé' ,
  `date_creation` DATETIME NOT NULL ,
  `date_maj` DATETIME NULL ,
  PRIMARY KEY (`id_groupe`, `id_utilisateur`) ,
  INDEX `fk_adhesion_idgroupe` (`id_groupe` ASC) ,
  INDEX `fk_adhesion_idutilisateur` (`id_utilisateur` ASC) ,
  CONSTRAINT `fk_adhesion_idgroupe`
    FOREIGN KEY (`id_groupe` )
    REFERENCES `groupe` (`id_groupe` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_adhesion_idutilisateur`
    FOREIGN KEY (`id_utilisateur` )
    REFERENCES `utilisateur` (`id_utilisateur` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `tag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tag` ;

CREATE  TABLE IF NOT EXISTS `tag` (
  `id_tag` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `libelle` TEXT NOT NULL ,
  `date_creation` DATETIME NOT NULL ,
  `date_maj` DATETIME NULL ,
  PRIMARY KEY (`id_tag`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `tag_groupe`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tag_groupe` ;

CREATE  TABLE IF NOT EXISTS `tag_groupe` (
  `id_groupe` INT UNSIGNED NOT NULL ,
  `id_tag` INT UNSIGNED NOT NULL ,
  `id_utilisateur` INT UNSIGNED NOT NULL COMMENT 'ID de l\'utilisateur qui a ajouté le tag pour le groupe' ,
  `date_creation` DATETIME NOT NULL ,
  `date_maj` DATETIME NULL ,
  PRIMARY KEY (`id_groupe`, `id_tag`, `id_utilisateur`) ,
  INDEX `fk_taggroupe_idgroupe` (`id_groupe` ASC) ,
  INDEX `fk_taggroupe_idtag` (`id_tag` ASC) ,
  INDEX `fk_taggroupe_idutilisateur` (`id_utilisateur` ASC) ,
  CONSTRAINT `fk_taggroupe_idgroupe`
    FOREIGN KEY (`id_groupe` )
    REFERENCES `groupe` (`id_groupe` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_taggroupe_idtag`
    FOREIGN KEY (`id_tag` )
    REFERENCES `tag` (`id_tag` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_taggroupe_idutilisateur`
    FOREIGN KEY (`id_utilisateur` )
    REFERENCES `utilisateur` (`id_utilisateur` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `publication`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `publication` ;

CREATE  TABLE IF NOT EXISTS `publication` (
  `id_publication` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `type` ENUM('lien', 'article', 'document') NOT NULL ,
  `prive` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Indique si la publication est privée et donc visible et commentable uniquement par les membres et admin (1), ou si elle est visible par tous les visiteurs (0)' ,
  `date_creation` DATETIME NOT NULL ,
  `date_maj` DATETIME NULL ,
  `id_utilisateur` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_publication`) ,
  INDEX `fk_publication_idutilisateur` (`id_utilisateur` ASC) ,
  CONSTRAINT `fk_publication_idutilisateur`
    FOREIGN KEY (`id_utilisateur` )
    REFERENCES `utilisateur` (`id_utilisateur` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `tag_publication`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tag_publication` ;

CREATE  TABLE IF NOT EXISTS `tag_publication` (
  `id_tag` INT UNSIGNED NOT NULL ,
  `id_publication` INT UNSIGNED NOT NULL ,
  `id_utilisateur` INT UNSIGNED NOT NULL COMMENT 'ID de l\'utilisateur qui a ajouté le tag pour la publication' ,
  `date_creation` DATETIME NOT NULL ,
  `date_maj` DATETIME NULL ,
  PRIMARY KEY (`id_tag`, `id_publication`, `id_utilisateur`) ,
  INDEX `fk_tagpub_idtag` (`id_tag` ASC) ,
  INDEX `fk_tagpub_idpublication` (`id_publication` ASC) ,
  INDEX `fk_tagpub_idutilisateur` (`id_utilisateur` ASC) ,
  CONSTRAINT `fk_tagpub_idtag`
    FOREIGN KEY (`id_tag` )
    REFERENCES `tag` (`id_tag` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tagpub_idpublication`
    FOREIGN KEY (`id_publication` )
    REFERENCES `publication` (`id_publication` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tagpub_idutilisateur`
    FOREIGN KEY (`id_utilisateur` )
    REFERENCES `utilisateur` (`id_utilisateur` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `partenariat`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `partenariat` ;

CREATE  TABLE IF NOT EXISTS `partenariat` (
  `id_groupe_demandeur` INT UNSIGNED NOT NULL ,
  `id_groupe_demande` INT UNSIGNED NOT NULL ,
  `statut` TINYINT(1) UNSIGNED NOT NULL COMMENT '0 : en attente\n1 : validé' ,
  `date_creation` DATETIME NOT NULL ,
  `date_maj` DATETIME NULL ,
  PRIMARY KEY (`id_groupe_demandeur`, `id_groupe_demande`) ,
  INDEX `fk_partenariat_idgroupedemandeur` (`id_groupe_demandeur` ASC) ,
  INDEX `fk_partenariat_idgroupedemande` (`id_groupe_demande` ASC) ,
  CONSTRAINT `fk_partenariat_idgroupedemandeur`
    FOREIGN KEY (`id_groupe_demandeur` )
    REFERENCES `groupe` (`id_groupe` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_partenariat_idgroupedemande`
    FOREIGN KEY (`id_groupe_demande` )
    REFERENCES `groupe` (`id_groupe` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `publication_groupe`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `publication_groupe` ;

CREATE  TABLE IF NOT EXISTS `publication_groupe` (
  `id_publication` INT UNSIGNED NOT NULL ,
  `id_groupe` INT UNSIGNED NOT NULL ,
  `date_creation` DATETIME NOT NULL ,
  `date_maj` DATETIME NULL ,
  PRIMARY KEY (`id_publication`, `id_groupe`) ,
  INDEX `fk_pubgroupe_idpublication` (`id_publication` ASC) ,
  INDEX `fk_pubgroupe_idgroupe` (`id_groupe` ASC) ,
  CONSTRAINT `fk_pubgroupe_idpublication`
    FOREIGN KEY (`id_publication` )
    REFERENCES `publication` (`id_publication` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pubgroupe_idgroupe`
    FOREIGN KEY (`id_groupe` )
    REFERENCES `groupe` (`id_groupe` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `publication_info`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `publication_info` ;

CREATE  TABLE IF NOT EXISTS `publication_info` (
  `id_publication_info` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `libelle` ENUM('image', 'url', 'texte_central', 'description', 'titre', 'date') NOT NULL ,
  `contenu` TEXT NOT NULL ,
  `id_publication` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_publication_info`) ,
  INDEX `fk_pubinfo_idpublication` (`id_publication` ASC) ,
  CONSTRAINT `fk_pubinfo_idpublication`
    FOREIGN KEY (`id_publication` )
    REFERENCES `publication` (`id_publication` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `tag_utilisateur`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tag_utilisateur` ;

CREATE  TABLE IF NOT EXISTS `tag_utilisateur` (
  `id_tag` INT UNSIGNED NOT NULL ,
  `id_utilisateur` INT UNSIGNED NOT NULL ,
  `date_creation` DATETIME NOT NULL ,
  `date_maj` DATETIME NULL ,
  PRIMARY KEY (`id_tag`, `id_utilisateur`) ,
  INDEX `fk_tagutilisateur_idtag` (`id_tag` ASC) ,
  INDEX `fk_tagutilisateur_idutilisateur` (`id_utilisateur` ASC) ,
  CONSTRAINT `fk_tagutilisateur_idtag`
    FOREIGN KEY (`id_tag` )
    REFERENCES `tag` (`id_tag` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tagutilisateur_idutilisateur`
    FOREIGN KEY (`id_utilisateur` )
    REFERENCES `utilisateur` (`id_utilisateur` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `commentaire`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `commentaire` ;

CREATE  TABLE IF NOT EXISTS `commentaire` (
  `id_commentaire` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `contenu` TEXT NOT NULL ,
  `date_creation` DATETIME NOT NULL ,
  `date_maj` DATETIME NULL ,
  `id_utilisateur` INT UNSIGNED NOT NULL ,
  `id_publication` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_commentaire`) ,
  INDEX `fk_commentaire_idutilisateur` (`id_utilisateur` ASC) ,
  INDEX `fk_commentaire_idpublication` (`id_publication` ASC) ,
  CONSTRAINT `fk_commentaire_idutilisateur`
    FOREIGN KEY (`id_utilisateur` )
    REFERENCES `utilisateur` (`id_utilisateur` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_commentaire_idpublication`
    FOREIGN KEY (`id_publication` )
    REFERENCES `publication` (`id_publication` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `recommandation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `recommandation` ;

CREATE  TABLE IF NOT EXISTS `recommandation` (
  `id_groupe_recommandeur` INT UNSIGNED NOT NULL ,
  `id_groupe_recommande` INT UNSIGNED NOT NULL ,
  `date_creation` DATETIME NULL ,
  PRIMARY KEY (`id_groupe_recommandeur`, `id_groupe_recommande`) ,
  INDEX `fk_recommandation_idgrouperecommandeur` (`id_groupe_recommandeur` ASC) ,
  INDEX `fk_recommandation_idgrouperecommande` (`id_groupe_recommande` ASC) ,
  CONSTRAINT `fk_recommandation_idgrouperecommandeur`
    FOREIGN KEY (`id_groupe_recommandeur` )
    REFERENCES `groupe` (`id_groupe` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_recommandation_idgrouperecommande`
    FOREIGN KEY (`id_groupe_recommande` )
    REFERENCES `groupe` (`id_groupe` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `site_social`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `site_social` ;

CREATE  TABLE IF NOT EXISTS `site_social` (
  `id_site_social` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `libelle` VARCHAR(32) NOT NULL COMMENT 'Libellé du site social (youtube, facebook, twitter, delicious...)' ,
  `avatar` TEXT NULL ,
  `date_creation` DATETIME NOT NULL ,
  `date_maj` DATETIME NULL ,
  PRIMARY KEY (`id_site_social`) ,
  UNIQUE INDEX `libelle_UNIQUE` (`libelle` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `profil_externe`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `profil_externe` ;

CREATE  TABLE IF NOT EXISTS `profil_externe` (
  `id_utilisateur` INT UNSIGNED NOT NULL ,
  `id_site_social` INT UNSIGNED NOT NULL ,
  `url` TEXT NOT NULL ,
  `date_creation` DATETIME NOT NULL ,
  `date_maj` DATETIME NULL ,
  PRIMARY KEY (`id_site_social`, `id_utilisateur`) ,
  INDEX `fk_profil_externe_id_utilisateur` (`id_utilisateur` ASC) ,
  INDEX `fk_profil_externe_id_site_social` (`id_site_social` ASC) ,
  CONSTRAINT `fk_profil_externe_id_utilisateur`
    FOREIGN KEY (`id_utilisateur` )
    REFERENCES `utilisateur` (`id_utilisateur` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_profil_externe_id_site_social`
    FOREIGN KEY (`id_site_social` )
    REFERENCES `site_social` (`id_site_social` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `contact`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contact` ;

CREATE  TABLE IF NOT EXISTS `contact` (
  `id_utilisateur_demandeur` INT UNSIGNED NOT NULL ,
  `id_utilisateur_demande` INT UNSIGNED NOT NULL ,
  `statut` TINYINT(1) NOT NULL COMMENT '0 : en attente\n1 : validé' ,
  `date_creation` DATETIME NOT NULL ,
  `date_maj` DATETIME NULL ,
  PRIMARY KEY (`id_utilisateur_demandeur`, `id_utilisateur_demande`) ,
  INDEX `fk_contact_utilisateur_demandeur` (`id_utilisateur_demandeur` ASC) ,
  INDEX `fk_contact_utilisateur_demande` (`id_utilisateur_demande` ASC) ,
  CONSTRAINT `fk_contact_utilisateur_demandeur`
    FOREIGN KEY (`id_utilisateur_demandeur` )
    REFERENCES `utilisateur` (`id_utilisateur` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contact_utilisateur_demande`
    FOREIGN KEY (`id_utilisateur_demande` )
    REFERENCES `utilisateur` (`id_utilisateur` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
