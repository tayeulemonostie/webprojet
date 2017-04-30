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

/*Déclaraton des variables*/
global $bd;

/*Connection a la DB*/
try {
  $bd = new PDO('mysql:host=localhost;dbname=webprojet','root','root');
  /*activer les exeptions en cas d'erreur */
  $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOExeption $e){
  echo "Erreur de connection à la DB : " . $e->getMessage();
}
/*Générer le titre de la page*/
$varHTML = "<h1>Page de l'administrateur - ".$_SESSION['username']."</h1>".PHP_EOL;
/*Générer le menu de l'administrateur*/
$varHTML .=
"<div class='zoneMenu'>".PHP_EOL.
    "<h2>Menu</h2>".PHP_EOL.
    "<ul>".PHP_EOL.
    "<li><a href='./admin.php?menu=lst_user'>Lister les utilisateurs</a></li>".PHP_EOL.
    "<li><a href='./admin.php?menu=add_user'>Ajouter un utilisateur</a></li>".PHP_EOL.
    "<li><a href='./admin.php?menu=mod_user'>Modifier un utilisateur</a></li>".PHP_EOL.
    "<li><a href='./admin.php?menu=pwd_chng'>Changement de mot de passe</a></li>".PHP_EOL.
    "<li><a href='./admin.php?menu=pwd_hist'>Consulter l’historique</a></li>".PHP_EOL.
    "<li><a href='./admin.php?menu=chk_syst'>Consulter l’état du système</a></li>".PHP_EOL.
    "<li><a href='./admin.php?menu=quo_gest'>Gestion des quotas</a></li>".PHP_EOL.
    "<li><a href='./admin.php?menu=clr_sess'>Fermer la session</a></li>".PHP_EOL.
    "</ul>".PHP_EOL.
"</div>".PHP_EOL;
/*Générer le contenu selon où se situe l'administrateur*/

/*Acceuil par défaut*/
if (!isset($_GET['menu']))
{
  $varHTML .=
  "<div class='zoneContenu'>".PHP_EOL.
      "<h2>Page d'acceuil</h2>".PHP_EOL.
      "<p>Bienvenue sur l'interface SebYveAdmin</p>".PHP_EOL;
}
else
{
  /*SI ON CLIQUE SUR UN DES CHOIX DU MENU*/
 $varHTML .=
 "<div class='zoneContenu'>".PHP_EOL;
 switch ($_GET['menu'])
 {
  case 'lst_user':
      $varHTML .=
          "<h2>Liste des utilisateurs</h2>".PHP_EOL;
      $varHTML .=
          list_user($bd);
          break;
  case 'add_user':
      $varHTML .=
          "<h2>Ajouter un utilisateur</h2>".PHP_EOL;
      if (!isset($_POST["submit1"]))
      {
      $varHTML .=
        create_user($bd);
        break;
      }
  case 'mod_user':
      $varHTML .=
          "<h2>Modifier un utilisateur</h2>".PHP_EOL.
          "<p>Formulaire avec submit,Processus de validation,DB alter</p>".PHP_EOL;
          break;
  case 'pwd_chng':
      $varHTML .=
          "<h2>Modifier un mot de passe</h2>".PHP_EOL.
          "<p>Formulaire avec submit,Processus de validation,DB entry</p>".PHP_EOL;
          break;
  case 'pwd_hist':
      $varHTML .=
          "<h2>Historique des changements de mot de passe</h2>".PHP_EOL.
          "<p>Query à la DB,afficher dans un tableau</p>".PHP_EOL;
          break;
  case 'chk_syst':
      $varHTML .=
          "<h2>État du système</h2>".PHP_EOL.
          "<p>Trouver les Query nécessaires pour: Nom de la machine,
          Mémoire vive totale vs utilisée, Espace disque totale vs utilisé,
          état du processeur,afficher</p>".PHP_EOL;
          break;
  case 'quo_gest':
      $varHTML .=
          "<h2>Gestion des quotas</h2>".PHP_EOL.
          "<p>Faire un premier formulaire avec 2 choix: 1. Voir les quotas
          -->>Query DB et Unix,afficher 2. Modifier le quota -->> Formulaire avec
          submit,Processus de validation,DB entry Unix entry</p>".PHP_EOL;
          break;
  case 'clr_sess':
      $varHTML .=
         "<h2>Confirmation la fermeture de session</h2>".PHP_EOL.
         "<form action='./admin.php?menu=clr_sess' method='POST'>".PHP_EOL.
			   "<input type='submit' name ='ctrl_nouvSess' width='50px' value='Quitter la session'</input>".PHP_EOL.
         "<input type='submit' name ='ctrl_backMain' width='50px' value='Retour'</input>".PHP_EOL.
			   "</form>".PHP_EOL;
          break;
  case 'conf_page':
      $varHTML .=
         "<h2>Confirmation des changements à la base de donnée</h2>".PHP_EOL.
         "<form action='./admin.php?menu=conf_page' method='POST'>".PHP_EOL.
         "<input type='submit' name ='ctrl_nouvSess' width='50px' value='Ajouter'</input>".PHP_EOL.
         "<input type='submit' name ='ctrl_backMain' width='50px' value='Annuler'</input>".PHP_EOL.
         "</form>".PHP_EOL;
          break;
  default:
          break;
 }
 $varHTML .=
 "</div>".PHP_EOL;

}

if(isset($_POST['ctrl_backMain']))
{
  header('Location: ./admin.php');
}

if(isset($_POST['ctrl_nouvSess']))
{
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
   </body>
 </html>
