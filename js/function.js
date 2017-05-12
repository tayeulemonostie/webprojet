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
