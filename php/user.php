<?PHP
/****************************************
   Fichier : user.php
   Auteur : Sébastien Corbeil & Yves distéfano
   Fonctionnalité : Fichier d'acceuil du site d'administration
   Date : 11 avril 2017
   Historique de modifications :
   Date               Nom                   Description
   =========================================================
   11-04-2017         Sébastien             Création du fichier
   16-04-2017         Sébastien             Connection à la BD
   13-05-2017         Yves                  Optimis. des buttons submit et cancel
                                            reglé bogue line 56, mod form change pw
                                            Rendu à CSS + Regex
****************************************/


/*Démarage de session*/
error_reporting(0);
session_start();

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

/*===== section link php ======*/
require "functions.php";

/*Génération de la page d'utilisateur ordinaire*/
$varHTML = "<h1>Bienvenue " . $_SESSION['username'] . " !" . "</h1>" . PHP_EOL;
$varHTML .= "<div class='zoneMenu'>".PHP_EOL.
            "<h1>Menu</h1>".PHP_EOL.
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
if (!isset($_GET['menu']))
{
    $varZoneContenue = "<h2>Tableau de Bord</h2>" . PHP_EOL .
                     "<p>Bienvenue sur l'interface de Gestion SebYvesAdmin</p>" . PHP_EOL;

}
//il fallait placer un else sinon il entre dans le switch pour rien (réglé message UNDEFINED LINE 56)
else
{
  switch ($_GET['menu'])
  {
    case 'quota_user':
      $varZoneContenue = "<h2>Utilisation du quota: ".quotaUser($_SESSION['login'])." </h2>" . PHP_EOL;
      break;
    case 'contact_admin':
      $varZoneContenue = "<h2>Formulaire pour contacter l'administrateur</h2>" . PHP_EOL .
                          contactAdmin() . PHP_EOL;
      if(isset($_POST['objet']) && $_POST['message']){
          mailtoadmin($_POST['objet'], $_POST['message']);
      }
      break;
    case 'clr_sess':
      $varZoneContenue = "<h2>Confirmation la fermeture de session</h2>".PHP_EOL.
      "<form action='./user.php?menu=clr_sess' method='POST'>".PHP_EOL.
      "<table align='center'>" . PHP_EOL . "<tr>" . PHP_EOL .
      "<td><input type='submit' class='button' name ='ctrl_nouvSess' width='50px' value='Quitter la session'</input></td>".PHP_EOL.
      "<td><input type='button' class='button' onclick='FlagMainU()' width='50px' value='Retour'></input></td>" . PHP_EOL.
      "</form>".PHP_EOL . "</tr>" . PHP_EOL . "</table>" . PHP_EOL;
      break;
    case 'chmdp':
      $varZoneContenue = "<h2>Changement de mot de passe<h2>" . PHP_EOL;
      $varZoneContenue .= pwd_chngForm ("user", $bd);
      break;
   case 'confchmdp':
      $varZoneContenue = "<h2>Confirmation du changement de mot de passe</h2>". PHP_EOL;
      $errorflag = validationFormulaire($_POST['old_pass'], $_POST['new_pass'], $_POST['pass_confirm']);
      switch ($errorflag) {
        //aucun erreur
        case 0:
          $varZoneContenue .= "<table align='center'>" . PHP_EOL .
                              "<tr>" . PHP_EOL .
                              "<form action='./user.php?menu=mdptodo' method='POST'>".PHP_EOL.
                              "<td align='center'><input type='submit' class='button' name ='ctrl_conf' width='50px' value='Modifier'</input></td>".PHP_EOL.
                              "<td align='center'><input type='button' class='button' onclick='FlagMainU()' value='Annuler'></input></td>".PHP_EOL.
                              "<input type='hidden' name='old_pass' value='".$_POST['old_pass']."''></input>" . PHP_EOL.
                              "<input type='hidden' name='new_pass' value='".$_POST['new_pass']."'></input>" . PHP_EOL.
                              "<input type='hidden' name='pass_confirm' value='".$_POST['pass_confirm']."'></input>" . PHP_EOL.
                              "</form>". PHP_EOL . "</tr>" . PHP_EOL . "</table>" . PHP_EOL;
          break;
        //erreur -> meme mot de pass utiliser comme nouveau
        case 1:
          $varZoneContenue .= "<table align='center'>" . PHP_EOL .
                              "<tr>" . PHP_EOL .
                              "<td>Veuillez ne pas utiliser votre ancien mot de passe pour votre nouveau.</td>" . PHP_EOL .
                              "</tr>" . PHP_EOL .
                              "<tr>" . PHP_EOL .
                              "<form action='./user.php?menu=chmdp' method='POST'>".PHP_EOL.
                              "<td align='center'><input type='submit' class='button' name ='ctrl_conf' width='50px' value='Modifier'</input>".PHP_EOL.
                              "<input type='button' class='button' onclick='FlagMainU()' value='Annuler'></input></td>".PHP_EOL.
                              "</form>".PHP_EOL . "</tr>" . PHP_EOL . "</table>" . PHP_EOL;
          break;
        // erreur ->  les nouveaus password (new et confirm) corresponde pas
        case 2:
          $varZoneContenue .= "<table align='center'>" . PHP_EOL .
                              "<tr>" . PHP_EOL .
                              "<td>Votre nouveau de passe et la confirmation du mot de passe ne correspond pas.</td>" . PHP_EOL .
                              "</tr>" . PHP_EOL .
                              "<tr>" . PHP_EOL .
                              "<form action='./user.php?menu=chmdp' method='POST'>".PHP_EOL.
                              "<td align='center'><input type='submit' class='button' name ='ctrl_conf' width='50px' value='Modifier'</input>".PHP_EOL.
                              "<input type='button' class='button' onclick='FlagMainU()' value='Annuler'></input></td>".PHP_EOL.
                              "</form>".PHP_EOL . "</tr>" . PHP_EOL . "</table>" . PHP_EOL;
          break;
        // erreur -> 1 ou plusieurs champs vides
        case 3:
          $varZoneContenue .= "<table align='center'>" . PHP_EOL .
                              "<tr>" . PHP_EOL .
                              "<td>Veuillez ne laisser aucun champs vide avec une \"*\"</td>" . PHP_EOL .
                              "</tr>" . PHP_EOL .
                              "<tr>" . PHP_EOL .
                              "<form action='./user.php?menu=chmdp' method='POST'>".PHP_EOL.
                              "<td align='center'><input type='submit' class='button' name ='ctrl_conf' width='50px' value='Modifier'</input>".PHP_EOL.
                              "<input type='button' class='button' onclick='FlagMainU()' value='Annuler'></input></td>".PHP_EOL.
                              "</form>".PHP_EOL . "</tr>" . PHP_EOL . "</table>" . PHP_EOL;
        break;
      }
      break;
    case 'mdptodo':
      changementmotdepasse($_POST['old_pass'], $_POST['new_pass'], $bd);
      $varZoneContenue =  "<h2>Changement de mot de passe effectué</h2>" . PHP_EOL.
                          "<table align='center'>" . PHP_EOL .
                          "<tr>" . PHP_EOL .
                          "<form action='./user.php' method='POST'>".PHP_EOL.
                          "<td align='center'><input type='button' class='button' onclick='FlagMainU()' width='50px' value='Retour'></input></td>" . PHP_EOL.
                          "</form>".PHP_EOL . "</tr>" . PHP_EOL . "</table>" . PHP_EOL;
      break;
  }
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
     <script type="text/javascript" src="../js/function.js"></script>
   </head>
   <body class="backgroundDF">
     <?PHP echo $varHTML ?>
     <div class="zoneContenu">
       <?PHP echo $varZoneContenue ?>
     </div>
   </body>
 </html>
