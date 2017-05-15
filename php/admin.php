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
if (isset($_SESSION['username']))
{
  if ($_SESSION['departement'] == 4)
  {
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
              list_user($bd,0);
              break;
      case 'add_user':
          $varHTML .=
              "<h2>Ajouter un utilisateur</h2>".PHP_EOL;
          $varHTML .=
              create_user($bd);
              break;
      case 'mod_user':
          $varHTML .=
              "<h2>Modifier un utilisateur</h2>".PHP_EOL.
              mod_user($bd);
              break;
      case 'pwd_chng':
          $varHTML .=
              "<h2>Modifier un mot de passe</h2>".PHP_EOL.
              pwd_chngForm("admin", $bd);
              break;
      case 'pwd_hist':
          $varHTML .=
              "<h2>Historique des changements de mot de passe</h2>".PHP_EOL;
          $varHTML .=
              Generate_PwHistory();
              break;
      case 'chk_syst':
          $varHTML .=
              "<h2>État du système</h2>".PHP_EOL.
              "<table id='tablesysetat' align='center'>" . PHP_EOL.
              "<tr>
                <td class='etatsys'>Nom de la machine:</td>
                <td class='etatsys'>".exec("hostname") . "</td>
              </tr>". PHP_EOL.
              "<tr>
                <td class='etatsys'>Utilisation du CPU : </td>
                <td class='etatsys'>". CPUusage() ."</td>
              </tr>". PHP_EOL.
              "<tr>
                <td class='etatsys'>Utilisation de la RAM : </td>
                <td class='etatsys'>". RAM() ."</td>
              </tr>". PHP_EOL.
              "<tr>
                <td class='etatsys'>Espace libre du disque OS : </td>
                <td class='etatsys'>".hddusage("/")."</td>
              </tr>". PHP_EOL.
              "<tr>
                <td class='etatsys'>Espace libre du disque Quota : </td>
                <td class='etatsys'>".hddusage("/quota")."</td>
              </tr>". PHP_EOL;
              break;
      case 'quo_gest':
          $varHTML .=
              "<h2>Gestion des quotas</h2>".PHP_EOL.
              "<form action='./admin.php?menu=viewQuota' method='POST'>".PHP_EOL.
              "<input type='submit' name ='viewQuota' width='50px' value='Voir les quotas'</input>".PHP_EOL."</form>".PHP_EOL.
              "<form action='./admin.php?menu=editQuota' method='POST'>".PHP_EOL.
              "<input type='submit' name ='editQuota' width='50px' value='Modifier les quotas'</input>".PHP_EOL."</form>".PHP_EOL;
              break;
      case 'viewQuota':
          $varHTML .=
              "<h2>Liste des quotas</h2>".PHP_EOL.
              list_user($bd,1);
              break;
      case 'editQuota':
          $varHTML .=
              "<h2>Modifier le quota</h2>".PHP_EOL;
              break;
      case 'clr_sess':
          $varHTML .=
             "<h2>Confirmation la fermeture de session</h2>".PHP_EOL.
             "<form action='./admin.php?menu=clr_sess' method='POST'>".PHP_EOL.
    			   "<input type='submit' name ='ctrl_nouvSess' width='50px' value='Quitter la session'</input>".PHP_EOL.
             "<input type='button' onclick='FlagMain()' value='Retour'></input>".PHP_EOL.
    			   "</form>".PHP_EOL;
              break;
      case 'conf_page':
          $varHTML .=
             "<h2>Confirmation de l'ajout à la base de donnée</h2>".PHP_EOL.
             //c'est à l'intérieur de cette fonction que les variables session pour les requêtes sont crées
             conf_create($bd).
             "<form action='./admin.php?menu=user_todo' method='POST'>".PHP_EOL.
             "<input type='submit' name ='ctrl_AddUser' width='50px' value='Ajouter'</input>".PHP_EOL.
             "<input type='button' onclick='FlagMain()' value='Annuler'></input>".PHP_EOL.
             "</form>".PHP_EOL;
              break;
      case 'confUserMod_page':
          $varHTML .=
            "<h2>Confirmation de la modification à la base de donnée</h2>".PHP_EOL.
              conf_modify($bd).
              "<form action='./admin.php?menu=mod_todo' method='POST'>".PHP_EOL.
              "<input type='submit' name ='ctrl_modUser' width='50px' value='Modifier'</input>".PHP_EOL.
              "<input type='button' onclick='FlagMain()' value='Annuler'></input>".PHP_EOL.
              "</form>".PHP_EOL;
              break;
      case 'confchmdp':
          $varHTML .=
              "<h2>Confirmation de la modification à la base de donnée</h2>".PHP_EOL.
              conf_pswd($bd).
              "<form action='./admin.php?menu=pswd_todo' method='POST'>".PHP_EOL.
              "<input type='submit' name ='ctrl_PWChng' width='50px' value='Modifier'</input>".PHP_EOL.
              "<input type='button' onclick='FlagMain()' value='Annuler'></input>".PHP_EOL.
              "</form>".PHP_EOL;
              break;
      //Lorsque l'admin confirme l'ajout d'un utilisateur (L'exécution en background)
      case 'user_todo':
          if ($_POST['ctrl_AddUser'] == "Ajouter")
          {
            $_SESSION['ChangeData'] = True;
          }
          else
          {
            header('Location: ./admin.php?menu=add_user');
          }
          if ($_SESSION['ChangeData'] == True)
          {
            add_user_Unix_DB($bd);
            $varHTML .=
            "<h2>Utilisateur ajouté!</h2>".PHP_EOL.
            "<form action='./admin.php' method='POST'>".PHP_EOL.
            "<input type='button' onclick='FlagMain()' value='Retour'></input>".PHP_EOL.
            "</form>".PHP_EOL;
          }
          break;
      //Lorsque l'admin confirme la modification à l'utilisateur
      case 'mod_todo':
          if ($_POST['ctrl_modUser'] == "Modifier")
          {
            $_SESSION['ChangeData'] = True;
          }
          else
          {
            header('Location: ./admin.php?menu=mod_user');
          }
          if ($_SESSION['ChangeData'] == True)
          {
            $bd->query("UPDATE Usagers_description SET ".$_SESSION['champModBD']." = '".$_SESSION['data']."' WHERE Usagers_description.usager_ID = ".$_SESSION['usager_ID']);
            $varHTML .=
            "<h2>Utilisateur modifié!</h2>".PHP_EOL.
            "<form action='./admin.php' method='POST'>".PHP_EOL.
            "<input type='button' onclick='FlagMain()' value='Retour'></input>".PHP_EOL.
            "</form>".PHP_EOL;
          }
          else
          {
            header('Location: ./admin.php?menu=mod_user');
          }
          break;
      case 'pswd_todo':
            if ($_POST['ctrl_PWChng'] == "Modifier")
              {
                $_SESSION['ChangeData'] = True;
              }
              else
              {
                header('Location: ./admin.php?menu=pwd_chng');
              }
              if ($_SESSION['ChangeData'] == True)
              {
                $bd->query("UPDATE Comptes SET expiration_password=NOW() + INTERVAL 90 DAY, user_password='".$_SESSION['data']."' WHERE Comptes.compte_ID='".$_SESSION['ID_usager']."';");
                $bd->query("INSERT INTO Historique_password (historique_ID, usager_ID, modif_admin, date_modif, ancien_password) VALUES (NULL, '".$_SESSION['ID_usager']."', '1', CURRENT_DATE(), '".$_SESSION['OldPassUser']."');");
                exec(".././script.sh ".$_SESSION['nom_utilisateur']." ".$_SESSION['data']);
                $varHTML .=
                "<h2>Utilisateur modifié!</h2>".PHP_EOL.
                "<form action='./admin.php' method='POST'>".PHP_EOL.
                "<input type='button' onclick='FlagMain()' value='Retour'></input>".PHP_EOL.
                "</form>".PHP_EOL;
              }
              else
              {
                header('Location: ./admin.php?menu=pwd_chng');
              }
      default:
          break;
     }
     $varHTML .=
     "</div>".PHP_EOL;
    }
    if(isset($_POST['ctrl_nouvSess']))
    {
      session_destroy();
      header('Location: ./index.php');
    }
  }
  else
  {
    header('Location: ./user.php');
  }
}
else
{
  header('Location: ./index.php');
}
 ?>

 <!DOCTYPE html>
 <html>
   <head>
     <title>WEBPROJET</title>
     <meta charset="UTF-8"/>
     <link rel="stylesheet" type="text/css" href="../css/styles.css">
     <script type="text/javascript" src="../js/function.js"></script>
   </head>
   <body>
     <?PHP echo $varHTML ?>
   </body>
 </html>
