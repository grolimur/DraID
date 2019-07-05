<!DOCTYPE html>
<html>
<head>
<link type="text/css" rel="stylesheet" href="styles.css" />
</head>
<body>
  <h1>DOI registration agency IDentifier</h1>
  <form action="index.php" method="get">
  <textarea rows="6" cols="50" name="d" placeholder="Copy DOI here (one per line if more than one)"></textarea><br/>
  <input type="submit" value="Submit">
  </form>
  <br/>
  <?php

  $dirtyDoiList = $_GET[d];
  // clean DOI list (removing empty lines)
  $cleanDoiList = preg_replace("/[\r\n]+/", " ", $dirtyDoiList);
  // Split DOI list in an array to be processed
  $doiArray = preg_split("/[\r\s\n]+/", $cleanDoiList);
  $count = count($doiArray);
  print '<table>
  <tr><th class="left">DOI</th><th class="right">Registration Agency</th></tr>
  ';
  // process provided DOI and display related registration agency
  for($i=0;$i<$count;$i++) {
    $url = 'https://api.crossref.org/works/'.$doiArray[$i].'/agency';
    $c = curl_init();
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_URL, $url);
    $curl = curl_exec($c);
    curl_close($c);
    $doi = json_decode($curl, true);
    print
    '<tr><td>'.$doiArray[$i].'</td><td>'.$doi["message"]["agency"]["label"].
    '</td></tr>';
  }
  print '</table>';
  ?>

  <hr/>
  <i>DraID by grolimur - v0.1.2 - July 5, 2019</i><br/>
  released under MIT License on <a href="https://github.com/grolimur/DraID" target="_blank">Github</a>
</body>
</html>
