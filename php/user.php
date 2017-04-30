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
session_start();

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

/*Génération de la Page Test de fonctions admin*/
$varHTML = "<h1>Bienvenue " . $_SESSION['username'] . " !" . "</h1>" . PHP_EOL;
$varHTML .= "<div class='zoneMenu'>".PHP_EOL.
    "<h2>Menu</h2>".PHP_EOL.
    "<ul>".PHP_EOL.
    "<li><a href=''>Changement de mot de passe</a></li>".PHP_EOL.
    "<li><a href=''>Aperçu de votre quotas</a></li>".PHP_EOL.
    "<li><a href=''>Fermer la session</a></li>".PHP_EOL.
    "</ul>".PHP_EOL.
"</div>".PHP_EOL;

$varQuota = quotaUser();

/*Vérification si les POST existent si oui on call la fonction authentification()*/

 ?>

 <!DOCTYPE html>
 <html>
   <head>
     <title>WEBPROJET</title>
     <meta charset="UTF-8"/>
     <link rel="stylesheet" type="text/css" href="../css/styles.css">
   </head>
   <body>
     <?PHP echo $varHTML ?>
     <div class="zoneContenu">
       <?PHP echo $varQuota ?>
     </div>
   </body>
 </html>
