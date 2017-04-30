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
  $_SESSION['login'] = strtolower($login);
  /*request pour grab username password et usager_ID*/
  $reqUser = $bd->query("SELECT nom_utilisateur, user_password, usager_ID, expiration_password FROM Comptes WHERE nom_utilisateur=\"$login\" AND user_password=\"$password\";");

  /*permet de savoir si j'aa recu une reponse de la BD*/
  $count = $reqUser->rowCount();


  if (!($count)){
    $reponse = "non";
  } else {

    /*je rammase le résulat*/
    $tabUser = $reqUser->fetch(PDO::FETCH_ASSOC);
    /*SI la date d'expiration de son password est la meme que aujourd'hui, impossible de logé*/
    if($tabUser['expiration_password'] == date("Y-m-d")){
      $reponse = "expire";
    } else {
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
  }
  return $reponse;
}
/*===FONCTION FORMULAIRE NOUVEL UTILISATEUR===*/

/*La fonction prend en paramètre la Connection à la base de donnée
Elle retourne un formulaire POST en contenu DIV */

function create_user ($bd){
  $contenuDiv = "";
  $UserNumber = 0;
  $DeptCount = 0;
  $var_vect_Dept  = [];
  $var_IndiceChamp = 0;
  $var_i = 1;
  $var_ValChamp    = "";
  unset($_POST);

  /*request 1. compte les user 2. compte les dept 3. liste des dept*/
  $req_1 = $bd->query("SELECT * FROM Usagers_description");
  $req_2 = $bd->query("SELECT nom_departement FROM Departements");

  /*compte les user + 1 pour donner le user_ID*/
  $UserNumber = ($req_1->rowCount())+1;
  /*compte les dept pour la taille de la liste*/
  $DeptCount = $req_2->rowCount();

    $contenuDiv =
    "<form method='POST' ACTION='./admin.php?menu=conf_page'>".PHP_EOL.
      "<table cellpadding='10px'>".PHP_EOL.
          "<tr>".PHP_EOL.
            "<td><label for='fname'>Nom de famille</label>".PHP_EOL.
            "<input type='text' id='fname' name='fname' maxlength='25'></input></td>".PHP_EOL.
            "<td><label for='name'>Prénom</label>".PHP_EOL.
            "<input type='text' id='name' name='name' maxlength='25'></input></td>".PHP_EOL.
          "</tr>".PHP_EOL.
          "<tr>".PHP_EOL.
            "<td><label for='poste'># de poste téléphonique</label>".PHP_EOL.
            "<input type='text' id='poste' name='poste' maxlength='4'></input></td>".PHP_EOL.
            "<td><label for='machine'># de machine</label>".PHP_EOL.
            "<input type='text' id='machine' name='machine' maxlength='4'></input></td>".PHP_EOL.
          "</tr>".PHP_EOL.
          "<tr>".PHP_EOL.
            "<td><label for='newuser'>Nom d'utilisateur</label>".PHP_EOL.
            "<input type='text' id='newuser' name='newuser' maxlength='12'></input></td>".PHP_EOL.
            "<td><label for='tamere'>Mot de passe</label>".PHP_EOL.
            "<input type='text' id='tamere' name='tamere' maxlength='12'></input></td>".PHP_EOL.
          "</tr>".PHP_EOL.
          "<tr>".PHP_EOL.
            "<td><label for='dept'>Nom du département</label>".PHP_EOL;

            //Construction dynamique de la liste des départements
      $contenuDiv .=
              "<select name='dept' id='dept' size=".$DeptCount."'>".PHP_EOL;
            //Tant que tu as des départements, créer une option
      while($var_vect_Dept = $req_2->fetch(PDO::FETCH_ASSOC))
      {
        foreach($var_vect_Dept as $var_IndiceChamp => $var_ValChamp)
        {
          $contenuDiv .=
          "<option value='".($var_IndiceChamp+$var_i)."'>".$var_ValChamp."</option>".PHP_EOL;
          $var_i++;
        }
      }
          //fermeture du select et on termine le formulaire
      $contenuDiv .=
              "</select>".PHP_EOL.
            "</td>".PHP_EOL.
            "<td><label for='quot'>Taille du quota (Go)</label>".PHP_EOL.
            "<input type='text' id='quot' name='quot' maxlength='2'></input></td>".PHP_EOL.
          "</tr>".PHP_EOL.
          "<tr>".PHP_EOL.
            "<td align='right'>".PHP_EOL.
            "<input type='reset' name='cancelForm' value='Annuler'></input></td>".PHP_EOL.
            "<td>".PHP_EOL.
            "<input type='Submit' name='submit1' value='Soumettre'></input></td>".PHP_EOL.
          "</tr>".PHP_EOL.
        "</table>".PHP_EOL.
      "</form>".PHP_EOL;

    return $contenuDiv;
}
/*===FONCTION LISTER LES UTILISATEUR===*/
/*La fonction prend en paramètre la Connection à la base de donnée
Elle retourne une liste en contenu DIV que si on clique sur un user
on peut modifier ses informations (mod_user)*/

function list_user ($bd){

  $contenuDiv = "";
  $var_vect_User  = [];
  $var_IndiceChamp = 0;
  $var_i = 1;
  $var_ValChamp    = "";

  /*request pour grab tous les utilisateurs*/
  $reqUser = $bd->query("SELECT CONCAT(prenom,' ',nom) FROM Usagers_description");

  /*compte le nombre d'enregistrements*/
  $count = $reqUser->rowCount();

  if (!($count))
  {
    $contenuDiv = "<p>Aucun utilisateurs trouvés</p>";
  }
  else
  {
    /*start une liste*/
    $contenuDiv = "<ul>".PHP_EOL;
    /*tant que j'ai des user pour chaque user fait une liste */
    while($var_vect_User = $reqUser->fetch(PDO::FETCH_ASSOC))
    {
      foreach($var_vect_User as $var_IndiceChamp => $var_ValChamp)
      {
        $contenuDiv .=
        "<li><a href='./admin.php?menu=mod_user&user=".($var_IndiceChamp+$var_i)."'>".$var_ValChamp."</a></li>".PHP_EOL;
        $var_i++;
      }
    }
    /*ferme une liste*/
    $contenuDiv .= "</ul>".PHP_EOL;
  }
  return $contenuDiv;
}

/*=================== FONCTION POUR LE USER ===================*/

/*fonction quota*/
function quotaUser (){
  $varNomUser = $_SESSION['login'];
  //commande Linux qui permet de voire les info de quota d'un utilisateur Particulié
  $varCommandeResult = exec("sudo quota $varNomUser");
  //Je reformate ce que la commande me sort pour seulement avoir UN espace en chaque donnés
  $varCommandeResult = preg_replace('/\s+/', ' ', $varCommandeResult);
  //Split le résultat de la commande en plusieur string dans un tableau
  $TabCommandeResultSplit = explode(" ", $varCommandeResult);

  //commande de Débug
  //print_r($TabCommandeResultSplit);

  //Je récupere seulement lespace total et utilisé de l'utilisateur
  $varUserEspaceTotal = $TabCommandeResultSplit[4];
  $varUserEspaceUtil = $TabCommandeResultSplit[5];

  //Passage de Bytes en Mb pour la beauté de la chose
  $varUserEspaceUtil = (double)$varUserEspaceUtil / (1024*1024);
  $varUserEspaceTotal = (double)$varUserEspaceTotal / (1024*1024);

  $varUserEspaceUtil = round($varUserEspaceUtil, 6);
  //je véfifie si l'utilisateur à un quota
  if ($varUserEspaceTotal == 0){
    $varQuotaUser = "Vous n'avez pas de quota défini.";
  } else {
    $varQuotaUser = $varUserEspaceUtil . " Mb/" . $varUserEspaceTotal . " Mb utilisés.";
  }
  return $varQuotaUser;
}

/*fonction Contactert administrateur*/
function contactAdmin(){
  /*VOIRE COMMENT FAIRE DU AJAX POUR CA*/
}
 ?>
