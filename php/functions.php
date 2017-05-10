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

require "class.phpmailer.php";
require "class.smtp.php";
/*Déclaration de variable*/
/* Base de données */
define ("cst_JeuCaracBD"                  , "utf8");
define ("cst_JeuCaracBD_ReglesComparaison", "utf8_bin");

define ("cst_MySQLServeur" , "localhost");
define ("cst_MySQLCompte"  , "root");
define ("cst_MySQLMotPasse", "root");
define ("cst_MySQLBD"      , "webprojet");

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
      /*besoin pour les request dans fonctoion modifiepassword*/
      $_SESSION['ID_usager'] = $testing;
      /*mettre le passwrod sans variable session pour fonction changerpassword*/
      $_SESSION['OldPassUser'] = $tabUser['user_password'];
      /*request pour déterminer dans quel departmemnent lusager ce trouve*/
      $reqAdmin = $bd->query("SELECT departements_ID FROM Usagers_description WHERE usager_ID=$testing;");
      $tabUserDep = $reqAdmin->fetch(PDO::FETCH_ASSOC);

      $reqUser = $bd->query("SELECT prenom FROM Usagers_description WHERE usager_ID=$testing;");
      $nom_utilisateur = $reqUser->fetch(PDO::FETCH_ASSOC);
        $_SESSION['username'] = $nom_utilisateur['prenom'];
      /*si l'usager est en TI, il est administrateur*/
      if ($tabUserDep['departements_ID'] == 4){
        $_SESSION['departement'] = $tabUserDep['departements_ID'];
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
  /*request compte les user */
  $req_1 = $bd->query("SELECT * FROM Usagers_description");
  /*compte les user + 1 pour donner le user_ID*/
  $UserNumber = ($req_1->rowCount())+1;
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
            "<td><label for='notel'># d'utilisateur</label>".PHP_EOL.
            "<input type='text' id='notel' name='notel' maxlength='2' disabled='disabled' placeholder='".$UserNumber."'></input></td>".PHP_EOL.
            "<td><label for='notel'># de tél.(domicile)</label>".PHP_EOL.
            "<input type='text' id='notel' name='notel' maxlength='12' placeholder='999-999-9999'></input></td>".PHP_EOL.
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
      $contenuDiv .= Generate_Dept($bd);
            //Retour à la création du formulaire
      $contenuDiv .=
            "</td>".PHP_EOL.
            "<td><label for='quot'>Taille du quota (Go)</label>".PHP_EOL.
            "<input type='text' id='quot' name='quot' maxlength='2'></input></td>".PHP_EOL.
          "</tr>".PHP_EOL.
          "<tr>".PHP_EOL.
            "<td align='right'>".PHP_EOL.
            "<input type='reset' name='cancelForm' value='Annuler'></input></td>".PHP_EOL.
            "<td>".PHP_EOL.
            "<input type='Submit' name='submitCreate' value='Soumettre'></input></td>".PHP_EOL.
          "</tr>".PHP_EOL.
        "</table>".PHP_EOL.
      "</form>".PHP_EOL;

    return $contenuDiv;
}
/*===FONCTION CONFIRME LE NOUVEL UTILISATEUR===*/

/*La fonction prend en paramètre la Connection à la base de donnée
Elle recoit les valeur POST les stock puis affiche en contenu DIV */

function conf_create ($bd){
  $contenuDiv = "";
  $UserNumber = 0;
  $varDept = $_POST['dept'];

  //$in90daysTMP = mktime(0, 0, 0, date("m")  , date("d")+90, date("Y"));
  //$in90days = date("Y-m-d",$in90daysTMP);

  /*request compte les user*/
  $req_1 = $bd->query("SELECT * FROM Usagers_description");
  /*request pour afficher le département*/
  $req_2 = $bd->query("SELECT nom_departement FROM Departements WHERE departements_ID=".$varDept."");
  /*compte les user + 1 pour donner le user_ID*/
  $UserNumber = ($req_1->rowCount())+1;
  /*va permettre d'afficher le nom et non le ID du département*/
  $nomDept = $req_2->fetch(PDO::FETCH_ASSOC);

  $contenuDiv =
      "<table cellpadding='10px'>".PHP_EOL.
          "<tr>".PHP_EOL.
            "<td><label>Nom de famille: </label>".PHP_EOL.
            "<label class='bolddown'>".$_POST['fname']."</label></td>".PHP_EOL.
            "<td><label>Prénom: </label>".PHP_EOL.
            "<label class='bolddown'>".$_POST['name']."</label></td>".PHP_EOL.
          "</tr>".PHP_EOL.
          "<tr>".PHP_EOL.
            "<td><label># d'utilisateur: </label>".PHP_EOL.
            "<label class='bolddown'>".$UserNumber."</label></td>".PHP_EOL.
            "<td><label># de tél.(domicile): </label>".PHP_EOL.
            "<label class='bolddown'>".$_POST['notel']."</label></td>".PHP_EOL.
          "</tr>".PHP_EOL.
          "<tr>".PHP_EOL.
            "<td><label># de poste téléphonique: </label>".PHP_EOL.
            "<label class='bolddown'>".$_POST['poste']."</label></td>".PHP_EOL.
            "<td><label># de machine: </label>".PHP_EOL.
            "<label class='bolddown'>".$_POST['machine']."</label></td>".PHP_EOL.
          "</tr>".PHP_EOL.
          "<tr>".PHP_EOL.
            "<td><label>Nom d'utilisateur: </label>".PHP_EOL.
            "<label class='bolddown'>".$_POST['newuser']."</label></td>".PHP_EOL.
            "<td><label>Mot de passe: </label>".PHP_EOL.
            "<label class='bolddown'>".$_POST['tamere']."</label></td>".PHP_EOL.
          "</tr>".PHP_EOL.
          "<tr>".PHP_EOL.
            "<td><label>Nom du département: </label>".PHP_EOL.
            "<label class='bolddown'>".$nomDept['nom_departement']."</label></td>".PHP_EOL.
            "<td><label>Taille du quota (Go): </label>".PHP_EOL.
            "<label class='bolddown'>".$_POST['quot']."</label></td>".PHP_EOL.
          "</tr>".PHP_EOL.
        "</table>".PHP_EOL;
        /* STOCKER LES VALEURS POST EN SESSION POUR LES UTILISER LORS DE LA QUERY */
        /*Pour Usagers_description*/
        $_SESSION['usager_ID'] = $UserNumber;
        $_SESSION['nom'] = $_POST['fname'];
        $_SESSION['prenom'] = $_POST['name'];
        $_SESSION['no_tel_poste'] = $_POST['poste'];
        $_SESSION['no_tel_dom'] = $_POST['notel'];
        $_SESSION['no_machine'] = $_POST['machine'];
        $_SESSION['departements_ID'] = $_POST['dept'];
        $_SESSION['quota'] = $_POST['quot'];
        /*Pour Comptes*/
        $_SESSION['compte_ID'] = $UserNumber;
        $_SESSION['nom_utilisateur'] = $_POST['newuser'];
        $_SESSION['user_password'] = $_POST['tamere'];
        //$_SESSION['expiration_password'] = $in90days;

        $_SESSION['ChangeData'] = True;


/*=========SECTION POUR CRÉE LE USER SUR LINUX========*/

    return $contenuDiv;
}
/*===FONCTION CONFIRME LE NOUVEL UTILISATEUR===*/

/*La fonction prend en paramètre la Connection à la base de donnée
Elle recoit les valeur POST les stock puis affiche en contenu DIV */

function conf_modify ($bd){
  print_r($_POST);
  $contenuDiv = "<h2>Voyons voir les valeurs post transmise<h2>";
  /* STOCKER LES VALEURS POST EN SESSION POUR LES UTILISER LORS DE LA QUERY */
  $_SESSION['ChangeData'] = True;
  return $contenuDiv;
}
/*===FONCTION AJOUTER UN UTILISATEUR LINUX ET DANS LA DB===*/
/*La fonction prend en paramètre la Connection à la base de donnée
À ce stade-ci les données sont validées, donc il ne s'agit que de
pousser les query vers Linux et la base de donnée*/

function add_user_Unix_DB ($bd){
/*requests pour insérer dans la DB */
  $bd->query("INSERT INTO Usagers_description (usager_ID, nom, prenom, no_tel_poste, no_tel_dom, no_machine, departements_ID, quota)
              VALUES ('".$_SESSION['usager_ID']."', '".$_SESSION['nom']."', '".$_SESSION['prenom']."', '".$_SESSION['no_tel_poste']."',
                '".$_SESSION['no_tel_dom']."', '".$_SESSION['no_machine']."', '".$_SESSION['departements_ID']."', '".$_SESSION['quota']."')");

  $bd->query("INSERT INTO Comptes (compte_ID, usager_ID, nom_utilisateur, user_password, expiration_password)
              VALUES ('".$_SESSION['usager_ID']."', '".$_SESSION['usager_ID']."', '".$_SESSION['nom_utilisateur']."', '".$_SESSION['user_password']."',
                '".$_SESSION['expiration_password']."')");
  /*section pour UNIX*/
  exec("sudo useradd ".$_SESSION['nom_utilisateur']." -p ".$_SESSION['user_password']." -m");

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
        "<div><li><a href='./admin.php?menu=mod_user&user=".($var_IndiceChamp+$var_i).
        "'>".$var_ValChamp."</a></li></div>".PHP_EOL;
        $contenuDiv .= "<div>".PHP_EOL;
        $contenuDiv .= Generate_User($var_IndiceChamp+$var_i);
        $var_i++;
      }
    }
    /*ferme une liste*/
    $contenuDiv .= "</ul>".PHP_EOL;
  }
  return $contenuDiv;
}
/*===FONCTION GÉNÉRER UN UTILISATEUR===*/

/*La fonction prend en paramètre le usager_ID
Elle retourne un tableau avec les détails de l'utilisateur */

function Generate_User($usager_ID)
{
/*Variable générale*/
$contenu = "";
/*Variables de manipulations bd*/
$var_Requete = "SELECT nom_utilisateur AS 'Utilisateur',
                       user_password AS 'Mot de passe',
                       expiration_password AS 'Expiration',
                       no_tel_dom AS '# de tél.(domicile)',
                       no_tel_poste AS '# de poste téléphonique',
                       no_machine AS '# de machine',
                       nom_departement AS 'Nom du département',
                       quota AS 'Taille du quota (Go)'
                FROM Usagers_description
                JOIN webprojet.Departements ON Departements.departements_ID=Usagers_description.departements_ID
                JOIN webprojet.Comptes ON Comptes.usager_ID=Usagers_description.usager_ID
                WHERE Comptes.usager_ID=$usager_ID";
$obj_ResutatReq  = NULL;
$obj_InfoChamp   = NULL;
$var_vect_UnEnr  = [];  // Vecteur représentant la req. principale
$var_IndiceChamp = 0; // sert dans le for each ligne de la req. principale
$var_ValChamp    = "";
/* CONNEXION À LA BASE DE DONNÉES*/
/* Création d'un pointeur sur la BD */
$bd = new mysqli( cst_MySQLServeur, cst_MySQLCompte, cst_MySQLMotPasse, cst_MySQLBD );
/* Gestion des erreurs de connexion */
if ($bd->connect_errno)
{
   echo "Echec de connexion à la BD ". cst_MySQLBD . " , Err: " . $obj_BD->connect_error;
   exit;
}
/* Ajustement du format des transactions par défaut entre le client et la BD */
$bd->query ("SET NAMES         '".cst_JeuCaracBD."' COLLATE '".cst_JeuCaracBD_ReglesComparaison."'");
$bd->query ("SET CHARACTER_SET '".cst_JeuCaracBD."'");
// Exécution de la requête
$obj_ResutatReq = $bd->query($var_Requete);
//Créer l'entête du tableau
$contenu .= "<table class='UserDetails'>" . PHP_EOL . "<tr>" . PHP_EOL;

  while($obj_InfoChamp = $obj_ResutatReq->fetch_field())
  {
    // Info d'entête.
    $contenu .= "<th class='UserDetails'>".$obj_InfoChamp->name ."</th>" . PHP_EOL;
  }
  $contenu .= "</tr>". PHP_EOL;
//Ajouter les informations dans une rangée
  $contenu .= "<tr>" . PHP_EOL;
  while($var_vect_UnEnr = $obj_ResutatReq->fetch_array(MYSQLI_NUM))
    {
      // Récupération des informations de chaque champ dans une cellule
      foreach($var_vect_UnEnr as $var_IndiceChamp => $var_ValChamp)
		    {
		        $contenu .= "<td class='UserDetails'>".$var_ValChamp."</td>". PHP_EOL;
		    }
    }
    // Fermeture de la rangée et de la table
    $contenu .= "</tr>".PHP_EOL."</table>";
return $contenu;
}
/*===FONCTION GÉNÉRER LES DÉPARTEMENTS===*/

/*Elle retourne une liste à option  */

function Generate_Dept($bd)
{
/*Variable générale*/
$contenuDiv = "";
$DeptCount = 0;
$var_vect_Dept  = [];
$var_IndiceChamp = 0;
$var_i = 1;
$var_ValChamp    = "";
/*request liste des dept*/
$req_1 = $bd->query("SELECT nom_departement FROM Departements");
/*compte les dept pour la taille de la liste*/
$DeptCount = $req_1->rowCount();
//Construction dynamique de la liste des départements
$contenuDiv .=
  "<select name='dept' id='dept' size=".$DeptCount."'>".PHP_EOL;
//Tant que tu as des départements, créer une option
while($var_vect_Dept = $req_1->fetch(PDO::FETCH_ASSOC))
{
foreach($var_vect_Dept as $var_IndiceChamp => $var_ValChamp)
{
$contenuDiv .=
"<option value='".($var_IndiceChamp+$var_i)."'>".$var_ValChamp."</option>".PHP_EOL;
$var_i++;
}
}
//fermeture du select
$contenuDiv .=
  "</select>".PHP_EOL;
return $contenuDiv;
}
/*===FONCTION FORMULAIRE MODIFIER UTILISATEUR===*/

/*La fonction prend en paramètre la Connection à la base de donnée
Elle retourne un formulaire POST en contenu DIV */

function mod_user ($bd){
  $contenuDiv = "";
  $UserNumber = 0;
  $UserCount = 0;
  $var_vect_User  = [];
  $var_IndiceChamp = 0;
  $var_i = 1;
  $var_ValChamp    = "";
  $tempo = 0;

  /*request 1. sort les utilisateurs */
  $req_1 = $bd->query("SELECT CONCAT(prenom,' ',nom) FROM Usagers_description");
  /*compte les user + 1 pour donner le user_ID*/
  $UserCount = ($req_1->rowCount())+1;

    $contenuDiv =
    "<form method='POST' ACTION='./admin.php?menu=confUserMod_page'>".PHP_EOL.
      "<table cellpadding='10px'>".PHP_EOL.
          "<tr class='ModifyTop'>".PHP_EOL.
            "<td><label for='dept' class='ModifyTop'>Utilisateur à modifier: </label>".PHP_EOL;
            //Construction dynamique de la liste des utilisateurs
      $contenuDiv .=
              "<select name='user' id='user' size=".$UserCount."'>".PHP_EOL;
            //Tant que tu as des utilisateurs, créer une option
      while($var_vect_User = $req_1->fetch(PDO::FETCH_ASSOC))
      {
        foreach($var_vect_User as $var_IndiceChamp => $var_ValChamp)
        {
          //SI l'administrateur a cliqué sur le nom de l'utilisateur à partir de la liste
          if ((isset($_GET['user']) && ($_GET['user'] == $var_IndiceChamp+$var_i)))
          {
            $contenuDiv .=
            "<option value='".($var_IndiceChamp+$var_i)."' selected='selected'>".$var_ValChamp."</option>".PHP_EOL;
          }
          else
          {
            $contenuDiv .=
            "<option value='".($var_IndiceChamp+$var_i)."'>".$var_ValChamp."</option>".PHP_EOL;
          }
          $var_i++;
        }
      }
          //fermeture du select et on termine le formulaire
      $contenuDiv .=
              "</select>".PHP_EOL.
            "</td>".PHP_EOL.
            "<td><label for='dept' class='ModifyTop'>Info à modifier: </label>".PHP_EOL.
            "<select name='infoTag' id='infoTag' onclick='tayeule()' size='7'>".PHP_EOL.
            "<option value='1'>Nom de famille</option>".PHP_EOL.
            "<option value='2'>Prénom</option>".PHP_EOL.
            "<option value='3'># de tél.(domicile)</option>".PHP_EOL.
            "<option value='4'># de poste téléphonique</option>".PHP_EOL.
            "<option value='5'># de machine</option>".PHP_EOL.
            "<option value='6'>Nom d'utilisateur</option>".PHP_EOL;
            if (isset($_GET['deptFlag']))
            {
              $contenuDiv .= "<option value='7' selected='selected'>";
            }
            else
            {
              $contenuDiv .= "<option value='7'>";
            }
            $contenuDiv .=
            "Nom du département</option>".PHP_EOL.
            "</select>".PHP_EOL.
            "</td>".PHP_EOL.
            "<td valign='top' id='infoTagTD'>";
            if (isset($_GET['deptFlag']))
            {
              $contenuDiv .=
              "<label for='infoMod' class='ModifyTop'>Nouveau département: </label>";
              $contenuDiv .= Generate_Dept($bd);
            }
            $contenuDiv .=
            "</td>".PHP_EOL.
            "</tr>".PHP_EOL.
            "<tr display='inline-block'>".PHP_EOL.
            "<td valign='top'>".PHP_EOL.
            "<input type='submit' name='ctrl_backMain' value='Annuler'></input>".PHP_EOL.
            "<input type='Submit' name='submitModUser' value='Soumettre'></input></td>".PHP_EOL.
          "</tr>".PHP_EOL.
        "</table>".PHP_EOL.
      "</form>".PHP_EOL;

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
  $varFormulaire = "<form action='./user.php?menu=contact_admin' method='POST'>" . PHP_EOL .
                   "<label for='objet'>Objet : </label>" . PHP_EOL .
                   "<input type='text' name='objet' id='objet' size='80'></input>" . PHP_EOL .
                   "<textarea name='message' cols='120' rows='15' id='message' placeholder='Écrire votre message ici'></textarea>" . PHP_EOL .
                   "<input type='submit' name='ctrl_envoi_mail' width='50px' value='Envoyer'></input>" . PHP_EOL;
  return $varFormulaire;
}

function mailtoadmin($sujet, $message){
  $mail = new PHPMailer();
  $mail->IsSMTP(); // send via SMTP
  $mail->SMTPAuth = true; // turn on SMTP authentication
  $mail->Username = "websebyves@gmail.com"; // Enter your SMTP username
  $mail->Password = "Tabarnak2017"; // SMTP password
  $webmaster_email = "websebyves@gmail.com"; //Add reply-to email address
  $email="devaster.64@gmail.com"; // Add recipients email address
  $name="Yves"; // Add Your Recipient’s name
  $mail->From = $webmaster_email;
  $mail->FromName = "Webmaster";
  $mail->AddAddress($email,$name);
  $mail->AddReplyTo($webmaster_email,"Webmaster");
  //$mail->WordWrap = "<strong>50</strong>"; // set word wrap
  //$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
  //$mail->AddAttachment("/tmp/image.jpg”, “new.jpg"); // attachment
  $mail->IsHTML(true); // send as HTML

  $mail->Subject = $sujet;

  $mail->Body = $message;      //HTML Body
  //$mail->SMTPDebug = 2;
  $mail->AltBody = $message;     //Plain Text Body
  if(!$mail->Send()){
    echo "Mailer Error: " . $mail->ErrorInfo;
  } else {
    echo "Message has been sent";
  }
}

/*fonction pour changer de mot de passe*/
function changementmotdepasse($oldpass, $newpass, $confnewpass, $bd){

/*update expiration passwoird aussi*/
  if($_SESSION['OldPassUser'] == $oldpass){
    if($newpass == $confnewpass){
//
      $bd->query("UPDATE Comptes SET expiration_password=NOW() + INTERVAL 90 DAY, user_password=\"$newpass\" WHERE Comptes.compte_ID='".$_SESSION['ID_usager']."';");
      //request dans histopassword
      if($_SESSION['departement'] == 4){
        //mettre 1 a modif_admin
        $bd->query("INSERT INTO Historique_password (historique_ID, usager_ID, modif_admin, date_modif, ancien_password) VALUES (NULL, '".$_SESSION['ID_usager']."', '1', CURRENT_DATE(), '".$oldpass."');");
      } else{
        //request pour historique password
        $bd->query("INSERT INTO Historique_password (historique_ID, usager_ID, modif_admin, date_modif, ancien_password) VALUES (NULL, '".$_SESSION['ID_usager']."', '0', CURRENT_DATE(), '".$oldpass."');");
      }
    } else{
      $result = "passnotmatch";
      return $result;
    }
  } else{
      $result = "nooldpass";
      return $result;
  }

}
 ?>
