

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
    "<label for='infoMod'>Modifié pour: </label><input type='text' id='infoMod' name='infoMod' onchange='validateAny(0)'></input>";
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
      } else{var strUser = e.options[e.selectedIndex].value;
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

// Fonction qui valide n'importe quel regex

function validateAny($contexte)
{

  //REGEX TIME !!!!!!!!!
  //regex pour Numéro Telephomne
  var regTel = /\d{3}-\d{3}-\d{4}/;
  //regex utilisatble pour le nom et nom de famille (check si ya des numéro ou caractère spéciaux)
  var regNomprenom = /\d|\W/;
  //regex pour le num de machine et # de poste tél (check si a des lettre et ou caratère spéciauxe)
  var regmachineposte = /\D|\W/;
  //regex pour le nom d'utilisateur (check si il contient des lettre en 6 et 12 caratères)
  var regusername = /\w{6,12}/;
  //regex pour le password (check si  le password est entre 6 et 12 caratères)
  var regpassword = /(\w|\W){6,12}/;
  //regex pour le quota (check maximum 2 chiffres)
  var regquota = /(^\d\d+?){1,2}/;

  //si je ne sais pas quel type de champ valider (pour modifier un utilisateur)
  if ($contexte == 0)
  {
    // il va chercher le choix que j'ai cliqué
    var e = document.getElementById("infoTag");
    var strUser = e.options[e.selectedIndex].value;

    // ça c'est ce qui va peut permettre d'aller modifier l'intérieur de la TD (ex. ajouter un message d'erreur)
    // si tu as pas le temps, on a juste à faire un alert() + focus()
    var td = document.getElementById("infoTagTD");



    switch (strUser)
    {
      case '1':
        var tds = document.getElementById("infoMod").value;
        teste = regNomprenom.test(tds);
        if(regNomprenom.test(tds)){
          alert("Veuillez inscrire un nom sans nombre et caractère spéciaux.");
          document.getElementById("infoMod").focus();
        }
      break;
      case '2':
        var tds = document.getElementById("infoMod").value;
        teste = regNomprenom.test(tds);
        if(regNomprenom.test(tds)){
          alert("Veuillez inscrire un prenom sans nombre et caractère spéciaux.");
          document.getElementById("infoMod").focus();
        }
      break;
      case '3':
      var tds = document.getElementById("infoMod").value;
      teste = regTel.test(tds);
      if(regNomprenom.test(tds)){
        alert("Veuillez inscrire un numéro de téléphone valide (888-888-8888).");
        document.getElementById("infoMod").focus();
      }
      break;
      case '4':
        var tds = document.getElementById("infoMod").value;
        teste = regmachineposte.test(tds);
        if(regNomprenom.test(tds)){
          alert("Veuillez inscrire un poste téléphonique à 4 chiffres.");
          document.getElementById("infoMod").focus();
        }
      break;
      case '5':
        var tds = document.getElementById("infoMod").value;
        teste = regmachineposte.test(tds);
        if(regNomprenom.test(tds)){var strUser = e.options[e.selectedIndex].value;
          alert("Veuillez inscrire un numéro de mahcine à 4 chiffres.");
          document.getElementById("infoMod").focus();
        }
      break;
    }
  }
  else
  {
      //lorsque je mets directement un contexte dans le input (1 à 8)
      // ça c'est la fonction générique qu'on peut caller de partout quand c'est pas un contenu dynamique
      // jouer avec les tag ou refaire des scénario au besoin

      switch($contexte)
      {
        case 1:
          var fname = document.getElementById("fname").value;
          if(regNomprenom.test(fname)){
            alert("Veuillez inscrire un nom sans nombre et caractère spéciaux.");
            document.getElementById("fname").focus();
          }
        break;
        case 2:
          var name = document.getElementById("name").value;
          if(regNomprenom.test(name)){
            alert("Veuillez inscrire un prénom sans nombres et/ou caractères spéciaux.");
            document.getElementById("name").focus();
          }
        break;
        case 3:
          var notel = document.getElementById("notel").value;
          if(regTel.test(notel)){
            alert("Veuillez inscrire un numéro de téléphone valide (888-888-8888).");
            document.getElementById("notel").focus();
          }
        break;
        case 4:
          var poste = document.getElementById("poste").value;
          if(regmachineposte.test(poste)){
            alert("Veuillez inscrire un poste téléphonique à 4 chiffres");
            document.getElementById("poste").focus();
          }
        break;
        case 5:
        var machine = document.getElementById("machine").value;
        if(regmachineposte.test(machine)){
          alert("Veuillez inscrire un numéro de mahcine à 4 chiffres.");
          document.getElementById("machine").focus();
        }

        break;
        case 6:
        var newuser = document.getElementById("newuser").value;
        if(regusername.test(newuser)){
          alert("Veuillez inscrire un nom d'usager entre 6 et 12 caratères");
          document.getElementById("newuser").focus();
        }
        break;
        case 7:
          var tamere = document.getElementById("tamere").value;
          if(regpassword.test(tamere)){
            alert("Veuillez inscrire un mot de passe entre 6 et 12 caratères");
            document.getElementById("tamere").focus();
          }
        break;
        case 8:
        var quot = document.getElementById("quot").value;
        if(regquota.test(quot)){
          alert("Veuillez inscrire un quota entre 0 et 99");
          document.getElementById("quot").focus();
        }
        break;
        case 9:
        var fname = document.getElementById("fname").value;
        var quot = document.getElementById("quot").value;
        var tamere = document.getElementById("tamere").value;
        var newuser = document.getElementById("newuser").value;
        var machine = document.getElementById("machine").value;
        var poste = document.getElementById("poste").value;
        var notel = document.getElementById("notel").value;
        var name = document.getElementById("name").value;
        var e = document.getElementById("infoMod");

        if (fname == "" || quot == "" || tamere == "" || newuser == "" || machine == "" || poste == "" || notel == "" || name == "" || !(e.value)){
          alert("veuillez remplir tout les champs.");
          //document.getElementById("fname").focus();
          //window.location.href = 'http://localhost/webprojet/webprojet/php/admin.php?menu=add_user';
          return false;
        }

      /*
*/
        //alert('onsubmit add user!');
        break;
        case 10:
          var e = document.getElementById("user");
          var f = document.getElementById("infoTag");
          var g = document.getElementById("infoMod");
          if (!(e.value) || !(f.value) || !(g.value)){
            alert("veuillez remplir tout les champs.");
            return false;
          }
        break;
        case 11:
          var e = document.getElementById("user");
          var f = document.getElementById("new_pass");
          if(!(e.value) || !(f.value)){
              alert("veuillez remplir tout les champs.");
              return false;
          }
        break;
        case 12:
        var e = document.getElementById("user");
        var f = document.getElementById("infoMod");
        if(!(e.value) || !(f.value)){
          alert("veuillez remplir tout les champs.");
          return false;
        }
        break;

      }
  }
}
