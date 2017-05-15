// Fonction qui va permettre de rentrer une valeur Get pour appeler la liste des département

function FlagDept()
{
  var e = document.getElementById("infoTag");
  var strUser = e.options[e.selectedIndex].value;
  var td = document.getElementById("infoTagTD");
  //si l'administrateur clique sur le choix pour modifier le département
  if (strUser == 6)
  {

    window.location.href = 'http://localhost/webprojet/webprojet/php/admin.php?menu=mod_user&deptFlag=True';
  }
  // Sinon il remet le input standard
  else
  {
    td.innerHTML =
    "<label for='infoMod'>Modifié pour: </label><input type='text' id='infoMod' name='infoMod'></input>";
  }
  return strUser;
}
// Fonctions qui s'occupe de ramener à la page d'acceuil lorsqu'on annule

function FlagMain()
{
      window.location.href = 'http://localhost/webprojet/webprojet/php/admin.php';
}
function FlagMainU()
{
      window.location.href = 'http://localhost/webprojet/webprojet/php/user.php';
}


//fonctions pour vérifier formulaire changement password section user
function validationFormulaire(){
  var old_pass = document.getElementById("old_pass").value;
  var new_pass = document.getElementById("new_pass").value;
  var pass_confirm = document.getElementById("pass_confirm").value;
  var flagerror = false;
  //vérification tout champs non vide
  if(old_pass !== "" && new_pass !== "" && pass_confirm !== ""){
    //vérification que l'utilisateur ne marque pas lancien password comme nouveau
    if(old_pass === new_pass){
      //afficher un message d'erreur
      alert("veuillez ne pas utiliser votre ancien mot de passe pour votre nouveau.");
      //Lever de flag d'erreur
      flagerror = true;
    } else {
      //vérification que le nouveau password et confirmation sont pareil
      if(new_pass === pass_confirm){
      } else{
        //afficher un message d'erreur
        alert("Votre nouveau de passe et la confirmation du mot de passe ne correspond pas.");
        flagerror = true;
      }
    }
  } else {
    //afficher un message d'erreur
    alert("Veuillez ne laisser aucun champs vide avec une \"*\"");
    //on "reload la page pour qu'il puissse recommencer"
    flagerror = true;
  }
  if(flagerror === true){
    window.location.href = 'http://localhost/webprojet/webprojet/php/user.php?menu=chmdp';
  } else{
    document.userchgmdp.submit();
  }
}
