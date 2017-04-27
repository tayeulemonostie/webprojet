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

/*Démarage de session*/
session_start();

/*Déclaration de variable*/


/*===FONCTION D'AUTHENTIFICATION===*/
/*La fonction prend en paramètre les variable POST login et password entrer
par l'utilisateur et aussi la Connection à la base de donnée
Elle retourne une réponse si le user est valide en fonction du password*/

function authentification ($login, $password, $bd){
  $reponse = "";

  /*request pour grab username password et usager_ID*/
  $reqUser = $bd->query("SELECT nom_utilisateur, user_password, usager_ID FROM Comptes WHERE nom_utilisateur=\"$login\" AND user_password=\"$password\";");

  /*permet de savoir si j'aa recu une reponse de la BD*/
  $count = $reqUser->rowCount();


  if (!($count)){
    $reponse = "non";
  } else {
    /*je rammase le résulat*/
    $tabUser = $reqUser->fetch(PDO::FETCH_ASSOC);

    /*tu tableau, je récupère l'id de l'usager*/
    $testing = $tabUser['usager_ID'];

    /*request pour déterminer dans quel departmemnent lusager ce trouve*/
    $reqAdmin = $bd->query("SELECT departements_ID FROM Usagers_description WHERE usager_ID=$testing;");
    $tabUserDep = $reqAdmin->fetch(PDO::FETCH_ASSOC);

    $reqUser = $bd->query("SELECT prenom FROM Usagers_description WHERE usager_ID=$testing;");
    $nom_utilisateur = $reqUser->fetch(PDO::FETCH_ASSOC);
      $_SESSION['username'] = $nom_utilisateur['prenom'];
    /*si l'usager est en TI, il est administrateur*/
    if ($tabUserDep['departements_ID'] == 4){
      $reponse = "admin";
    }
    else {
      /*S'il est toute sauf admin(TI)*/
      $reponse = "user";
    }
  }
  return $reponse;
}


/*fonction quota*/
/*function quotaUser (){
  $vartest = exec('whoiam');
  return $vartest;
}
*/
 ?>
