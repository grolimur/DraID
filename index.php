<!DOCTYPE html>
<html>
<head>
<link type="text/css" rel="stylesheet" href="styles.css" />
</head>
<body>
  <h1>DOI registration agency IDentifier</h1>
  <form action="index.php" method="get">
  Copy a DOI here: <input name="d" type="text">
  <input type="submit" value="Submit">
  </form>
  <br/>
  <?php

  $d = $_GET[d];
  $url = 'https://api.crossref.org/works/'.$d.'/agency';
  $c = curl_init();
  curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($c, CURLOPT_URL, $url);
  $curl = curl_exec($c);
  curl_close($c);
  $doi = json_decode($curl, true);
  if($d) {print
    '<table>
    <tr><th class="left">DOI</th><th class="right">Registration Agency</th></tr>
    <tr><td>'.$d.'</td><td>'.$doi["message"]["agency"]["label"].
    '</td></tr>
    </table>';
  }
  ?>

  <hr/>
  <i>DraID by grolimur - v0.01 - April 2018</i><br/>
  released under MIT License
</body>
</html>
