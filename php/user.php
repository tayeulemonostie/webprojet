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

/*Génération de la page d'utilisateur ordinaire*/
$varHTML = "<h1>Bienvenue " . $_SESSION['username'] . " !" . "</h1>" . PHP_EOL;
$varHTML .= "<div class='zoneMenu'>".PHP_EOL.
            "<h2>Menu</h2>".PHP_EOL.
            "<ul>".PHP_EOL.
            "<li><a href='./user.php'>Page d'Acceuil</a></li>".PHP_EOL.
            "<li><a href='./user.php?menu=chmdp'>Changement de mot de passe</a></li>".PHP_EOL.
            "<li><a href='./user.php?menu=contact_admin'>Contacter l'administrateur</a></li>".PHP_EOL.
            "<li><a href='./user.php?menu=quota_user'>Aperçu de votre quotas</a></li>".PHP_EOL.
            "<li><a href='./user.php?menu=clr_sess'>Fermer la session</a></li>".PHP_EOL.
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
    $varZoneContenue = "<h2>Formulaire de contact de L'administrateur</h2>" . PHP_EOL .
                        contactAdmin() . PHP_EOL;
    if(isset($_POST['objet']) && $_POST['message']){
        mailtoadmin($_POST['objet'], $_POST['message']);
    }
    break;
  case 'clr_sess':
    $varZoneContenue = "<h2>Confirmation la fermeture de session</h2>".PHP_EOL.
    "<form action='./user.php?menu=clr_sess' method='POST'>".PHP_EOL.
    "<input type='submit' name ='ctrl_nouvSess' width='50px' value='Quitter la session'</input>".PHP_EOL.
    "<input type='submit' name ='ctrl_backMain' width='50px' value='Retour'</input>".PHP_EOL.
    "</form>".PHP_EOL;
    break;
  case 'chmdp':
    $varZoneContenue = "<h2>Changement de mot de passe<h2>" . PHP_EOL
                       . "<form action=./user.php?menu=chmdp method='POST'>" . PHP_EOL.
                       "<label for='old_pass'>Ancien mot de passe : </label>" . PHP_EOL.
                       "<input type='password' name='old_pass' id='old_pass'></input>" . PHP_EOL.
                       "<br/>" . PHP_EOL.
                       "<br/>" . PHP_EOL.
                       "<label for='new_pass'>Nouveau Password</label>" . PHP_EOL.
                       "<input type='password' name='new_pass' id='new_pass'></input>" . PHP_EOL.
                       "<br/>" . PHP_EOL.
                       "<br/>" . PHP_EOL.
                       "<label for='pass_confirm'>Confirmation Password</label>" . PHP_EOL.
                       "<input type='password' name='pass_confirm' id='pass_confirm'></input>";
}
/*pour la fermeture de session*/
if(isset($_POST['ctrl_backMain'])){
  header('Location: ./user.php');
}

 if(isset($_POST['ctrl_nouvSess'])) {
  session_destroy();
  header('Location: ./index.php');
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
