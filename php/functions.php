<?PHP
/****************************************
   Fichier : function.php
   Auteur : Sébastien Corbeil & Yves distéfano
   Fonctionnalité : Fichier contenant les différentes functions.
   Date : 11 avril 2017
   Historique de modifications :
   Date               Nom                   Description
   =========================================================
   11-04-2017         Sébastien             Création du fichier
   16-04-2017         Sébastien             Création fonction authentification
****************************************/



/*Déclaration de variable*/


/*===FONCTION D'AUTHENTIFICATION===*/
/*La fonction prend en paramètre les variable POST login et password entrer
par l'utilisateur et aussi la Connection à la base de donnée
Elle retourne une réponse si le user est valide en fonction du password*/
function authentification ($login, $password, $bd){
  $reponse = "";
  $reqUser = $bd->query("SELECT nom_utilisateur, user_password, usager_ID FROM Comptes WHERE nom_utilisateur=\"$login\" AND user_password=\"$password\";");
  $count = $reqUser->rowCount();
  $tabUser = $reqUser->fetch(PDO::FETCH_ASSOC);
  $reqAdmin = $bd->query("SELECT departements_ID FROM Usagers_description WHERE usager_ID=$tabUser[\'usager_ID\'];");
  echo $reqAdmin;
  $tabUserDep = $reqAdmin->fetch(PDO::FETCH_ASSOC);
  print_r($tabUserDep);
  if (!($count)){
    /*À ENLEVER*/
    $reponse = "l'utilisateur et/ou le mot de passe est incorrect.";
  } else {
    /*if (){

    }*/
    /*À ENLEVER*/
    $reponse = "L'utilisateur et password sont dans la BD ! :)";
  }
  return $reponse;
}


 ?>
