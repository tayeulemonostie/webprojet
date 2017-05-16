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

/*Démarage de session*/
error_reporting(0);
session_start();

$_SESSION['JSenabled'] = 0;
/*===== section link php ======*/
require "functions.php";

/*Déclaraton de variables*/
global $bd;
/*Connexion à la base de donnée*/
try {
  $bd = new PDO("mysql:host=localhost;dbname=webprojet", "root", "root");
  /*activer les exeptions en cas d'erreur */
  $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOExeption $e){
  echo "Échec lors de la connexion à la base de donnée... Voici l'erreur: " . $e->getMessage();
}
/*Génération de la page de login*/

$varHTML = "<div>" . PHP_EOL .
           "<h1>Page d'authentification</h1>" . PHP_EOL
           . "<h3>Veuillez inscrire votre nom d'utilisateur et mot de passe</h3>" . PHP_EOL
           . "<form method=\"POST\" action=\"index.php\">" . PHP_EOL
           . " </br><label>Login</label><input type=\"text\" name=\"login\"/> </br></br>" . PHP_EOL
           . "<label>Password</label><input type=\"password\" name=\"password\"/> </br>" . PHP_EOL
           . "</br> <input type=\"submit\" class='button' name=\"submit\" value=\"Se connecter\"/>" . PHP_EOL
           . "</form>" . PHP_EOL . "</div>";

/*Vérification si les POST existent si oui on call la fonction authentification()*/
if (isset($_POST["login"]) && isset($_POST["password"])){
  $AuthID = authentification($_POST["login"], $_POST["password"], $bd);
  if ($AuthID == "non")
  {
   $varHTML .= "</br></br>L'utilisateur et/ou le mot de passe n'existe pas";
  }
  elseif ($AuthID == "admin")
  {
    header('Location: ./admin.php');
  }
  elseif ($AuthID == "expire")
  {
      $varHTML .= "</br></br>Votre mot de passe est expiré.";
  }
  else
  {
    header('Location: ./user.php');
  }
}

 ?>

<!--Code HTML PEUT ETRE METTRE DANS UNE FONCTION.....-->
<!DOCTYPE html>
<html>
  <head>
    <title>WEBPROJET</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
  </head>
  <body class="backgroundDF">
    <?PHP echo $varHTML ?>
  </body>
</html>
