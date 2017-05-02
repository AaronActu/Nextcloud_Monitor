<?php

  $validate_server = false;

  if (isset($_POST['url']))
  {
    $url = $_POST['url'];
    $nextcloud_server = strtolower(parse_url($url, PHP_URL_HOST));
    if (!preg_match("#https?:\/\/[\w\.-]+\/status\.php$#i", $url)) { $url = "http://$nextcloud_server/status.php"; }
    $data = json_decode(@file_get_contents($url), true);


    if (isset($data['installed']) AND isset($data['maintenance']) AND isset($data['needsDbUpgrade']) AND isset($data['version']) AND isset($data['versionstring']) AND isset($data['productname']))
    {
      $validate_server = true;
      if ($data['installed'] == 1)  {
        $installed =
        '<tr>
          <th>Installation</th>
          <td class="success">Nextcloud est correctement installé.</td>
        </tr>';
      } else {
        $installed =
        '<tr>
          <th>Installation</th>
          <td class="danger"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>Le serveur n\'est pas installé correctement.</td>
        </tr>';
      }
      if ($data['maintenance'] == 1){
        $maintenance =
        '<tr>
          <th>Maintenance</th>
          <td class="warning"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>Le serveur est en cours de maintenance.</td>
         </tr>';
      } else {
        $maintenance =
        '<tr>
          <th>Maintenance</th>
          <td class="success">Le serveur n\'est pas en maintenance.</td>
         </tr>';
      }
      if ($data['needsDbUpgrade'] == 1) {
        $needsDbUpgrade =
        '<tr>
          <th>Database Upgrade</th>
          <td class="danger"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>La base de donnée doit être mise à jour !</td>
         </tr>';  } else {
        $needsDbUpgrade =
            '<tr>
              <th>Database Upgrade</th>
              <td class="success">La base de donnée est à jour.</td>
            </tr>';
      }
      if (!empty($data['version'])) {
        $version = $data['version'];
        if (str_replace(".", "", $version) >=	11012)  {
          $version =
          '<tr>
            <th>Version</th>
            <td class="success">'.$version.' Nextcloud est à jour.</td>
          </tr>';
        } else {
          $version =
              '<tr>
                <th>Version</th>
                <td class="warning">'.$version.' Nextcloud n\'est pas à jour.</td>
              </tr>';
        }
      }
      if (!empty($data['productname'])) {
        $productname =
        '<tr>
          <th>Nom</th>
          <td class="active">'.$data['productname'].' - <em>'.$nextcloud_server.'</em></td>
         </tr>';
      }
    }
  }

?>
<!DOCTYPE HTML>
<html>
 <head>
   <meta charset="utf-8"/>
   <title>Infos Nextcloud</title>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
 </head>
 <body>
   <div class="container" style="margin-top:7%;">
       <?php
       if ($validate_server == true)
       {
         echo "<div class='jumbotron'>
          <center><h3><p>Toutes les infos pratiques du serveur Nextcloud
          <em>$nextcloud_server</em> se trouvent sur cette page<p></h3></center>
         </div>
       <table class=\"table table-hover\">
         <caption>Informations du serveur Nextcloud <em>$nextcloud_server</em></caption>";
             (!empty($productname) AND print($productname));
             (!empty($installed) AND print($installed));
             (!empty($version) AND print($version));
             (!empty($maintenance) AND print($maintenance));
             (!empty($needsDbUpgrade) AND print($needsDbUpgrade));
             //(!empty() AND print());
             //(!empty() AND print());
             echo "</table>";
           } else {
             echo
             '<div class="jumbotron"><center>
              <form action="" method="post">
                <input type="url" name="url" class="form-control" onfocus="fill()" placeholder="L\'URL de votre serveur Nextcloud"><br/>
                <input type="submit" name="submit" class="btn btn-info" value="Obtenir les infos">
               </form>
              </center></div>';
           }
           ?>
  </div><!--.//Container-->
  <script>function fill()  {
      $('.form-control').val('http://');
    }</script>
 </body>
</html>
