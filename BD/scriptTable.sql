
DROP DATABASE IF EXISTS webprojet;
/*Création de la base de données*/
CREATE DATABASE IF NOT EXISTS webprojet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE webprojet;

/*Réinitialisation des tables s'ils existent*/
DROP TABLE IF EXISTS Usagers_description;
DROP TABLE IF EXISTS Comptes;
DROP TABLE IF EXISTS Historique_password;
DROP TABLE IF EXISTS Departements;


/*CRÉATION DES TABLES*/
/*Table qui recense les différents départements*/
CREATE TABLE Departements (
  departements_ID INT NOT NULL AUTO_INCREMENT,
  nom_departement VARCHAR(32) NOT NULL,

  /*déclaration de la primary keys*/
  PRIMARY KEY(departements_ID)
);



/*Création de la table de description des usagers*/
CREATE TABLE Usagers_description (
  usager_ID         INT NOT NULL AUTO_INCREMENT,
  nom               VARCHAR(64) NOT NULL,
  prenom            VARCHAR(64) NOT NULL,
  no_tel_poste      INT NOT NULL,
  no_tel_dom        VARCHAR(12) NOT NULL,
  no_machine        INT NOT NULL,
  departements_ID   INT NOT NULL,
  quota             FLOAT NOT NULL,

/*Création des FOREIGN KEY*/
  FOREIGN KEY fkDepartement_ID(departements_ID) REFERENCES Departements(departements_ID),

/*Création de la PRIMARY KEY*/
  PRIMARY KEY(usager_ID)
);

/*Table qui recense les info de login des utilisateurs*/
CREATE TABLE Comptes (
  compte_ID INT NOT NULL AUTO_INCREMENT,
  usager_ID INT NOT NULL,
  nom_utilisateur VARCHAR(32) NOT NULL,
  user_password VARCHAR(128) NOT NULL,
  expiration_password DATE NOT NULL,

  /*Création des FOREIGN KEY*/
  FOREIGN KEY fkusager_ID2(usager_ID) REFERENCES Usagers_description(usager_ID),
  /*Déclaration de la primary keys*/
  PRIMARY KEY(compte_ID)
);

/*Création de la table d'Historique de password*/
CREATE TABLE Historique_password (
  historique_ID     INT not NULL AUTO_INCREMENT,
  usager_ID         INT NOT NULL,
  date_modif        DATE NOT NULL,
  ancien_password   VARCHAR(128) NOT NULL,

  /*Création des foreign key*/
  FOREIGN KEY fkusager_ID1(usager_ID) REFERENCES Usagers_description(usager_ID),

  /*Création PRIMARY KEY*/
  PRIMARY KEY(historique_ID)
);
