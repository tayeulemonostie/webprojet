function tamere()
{
  var e = document.getElementById("infoTag");
  var strUser = e.options[e.selectedIndex].value;
  var td = document.getElementById("infoTagTD");

  if (strUser == 7)
  {

    td.innerHTML =
    "<label for='infoMod'>DÉPARTEMENT</label><input type='text' id='infoMod' name='infoMod'></input>";

  }
  else
  {
    td.innerHTML =
    "<label for='infoMod'>Modifié pour: </label><input type='text' id='infoMod' name='infoMod'></input>";
  }
  return strUser;
}
function tayeule()
{
  var e = document.getElementById("infoTag");
  var strUser = e.options[e.selectedIndex].value;
  var td = document.getElementById("infoTagTD");

  if (strUser == 7)
  {

    window.location.href = 'http://localhost/webprojet/webprojet/php/admin.php?menu=mod_user&deptFlag=True';
  }
  else
  {
    td.innerHTML =
    "<label for='infoMod'>Modifié pour: </label><input type='text' id='infoMod' name='infoMod'></input>";
  }
  return strUser;
}
