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

/*Génération de la page d'utilisateur ordinaire*/
$varHTML = "<h1>Bienvenue " . $_SESSION['username'] . " !" . "</h1>" . PHP_EOL;
$varHTML .= "<div class='zoneMenu'>".PHP_EOL.
            "<h2>Menu</h2>".PHP_EOL.
            "<ul>".PHP_EOL.
            "<li><a href='./user.php'>Page d'Acceuil</a></li>".PHP_EOL.
            "<li><a href=''>Changement de mot de passe</a></li>".PHP_EOL.
            "<li><a href='./user.php?menu=contact_admin'>Contacter l'administrateur</a></li>".PHP_EOL.
            "<li><a href='./user.php?menu=quota_user'>Aperçu de votre quotas</a></li>".PHP_EOL.
            "<li><a href=''>Fermer la session</a></li>".PHP_EOL.
            "</ul>".PHP_EOL.
            "</div>".PHP_EOL;

/*Génération Contenue de la Zone Contenue*/
/*Acceuil par défault*/
if (!isset($_GET['menu'])){
  $varZoneContenue = "<h2>Tableau de Bord</h2>" . PHP_EOL .
                     "<p>Bienvenue sur l'interface de Gestion SebYvesAdmin</p>" . PHP_EOL;
}
switch ($_GET['menu']) {
  case 'quota_user':
    $varZoneContenue = "<h2>Voici votre Quota disponible : </h2>" . PHP_EOL .
                       "<p>" . quotaUser() . "</p>" . PHP_EOL;
    break;

  case 'contact_admin':
    $varZoneContenue = "<h2>Formulaire de contact de L'administrateur</h2>" . PHP_EOL;

    break;
  /*default:
    # code...
    break;*/
}

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
       <?PHP echo $varZoneContenue ?>
     </div>
   </body>
 </html>
