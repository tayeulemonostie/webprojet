<?PHP
/****************************************
   Fichier : index.php
   Auteur : Sébastien Corbeil & Yves distéfano
   Fonctionnalité : Fichier d'acceuil du site d'administration
   Date : 11 avril 2017
   Historique de modifications :
   Date               Nom                   Description
   =========================================================
   11-04-2017         Sébastien             Création du fichier
   16-04-2017         Sébastien             Connection à la BD
****************************************/

/*==================================
PENSEZ À METTRE DISPLAY_ERROR = ON À OFF AVANT REMISE
DANS LE FICHIER /ETC/PHP/7.0/APACHE2/php.ini
=====================================*/

/*Démarage de session*/


/*===== section link php ======*/
require "functions.php";

/*Déclaraton de variables*/
global $bd;

/*c'est icitte crisse que je connect ma bite dans les données*/
try {
  $bd = new PDO("mysql:host=localhost;dbname=webprojet", "root", "root");
  /*activer les exeptions en cas d'erreur */
  $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "La BD du projet est connectée TABARNAK !!!";
}
catch(PDOExeption $e){
  echo "CALISE la bd a pas connect... tien vla l'erreur : " . $e->getMessage();
}

/*Génération de la Page Test de fonctions login*/
$varHTML = "<h1>Page Test de la fonction authentification()</h1>" . PHP_EOL
           . "<form method=\"POST\" action=\"index.php\">" . PHP_EOL
           . " </br> Login : <input type=\"text\" name=\"login\"/> </br></br>" . PHP_EOL
           . "Password : <input type=\"text\" name=\"password\"/> </br>" . PHP_EOL
           . "</br> <input type=\"submit\" name=\"submit\" value=\"Connect biatch\"/>" . PHP_EOL
           . "</form>";

/*Vérification si les POST existent si oui on call la fonction authentification()*/
if (isset($_POST["login"]) && isset($_POST["password"])){
  $crisse = authentification($_POST["login"], $_POST["password"], $bd);
  $varHTML = "</br></br>" . $crisse;
}

 ?>

<!--Code HTML PEUT ETRE METTRE DANS UNE FONCTION.....-->
<!DOCTYPE html>
<html>
  <head>
    <title>WEBPROJET</title>
  </head>
  <body>
    <?PHP echo $varHTML ?>
  </body>
</html>
