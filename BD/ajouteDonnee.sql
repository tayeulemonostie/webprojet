
/*Ajout différent département dans table départmement*/
USE webprojet;
INSERT INTO Departements (nom_departement)
  VALUES ('Direction'),
         ('Comptabilité'),
         ('Ressources Humaines'),
         ('TI'),
         ('Stagiaires');



/*Pour ajouter un utilisateur*/
use webprojet;
set @calise = (SELECT concat(left(nom,3), left(prenom,3)) FROM Usagers_description WHERE usager_ID=1);
insert INTO Comptes (usager_ID, nom_utilisateur, user_password, expiration_password)
VALUES (1, @calise, 'root', now() + INTERVAL 90 DAY );


/*Request pour grab user passeword user*/
SELECT nom_utilisateur, user_password FROM Comptes WHERE nom_utilisateur='CorSeb' AND user_password='root';

/*Get le no departe du user*/
SELECT departements_ID FROM Usagers_description WHERE usager_ID=1;
