i<!DOCTYPE html>
<html>
<head>
<link type="text/css" rel="stylesheet" href="styles.css" />
</head>
<body>
  <h1>DOI registration agency IDentifier</h1>
  <form action="index.php" method="get">
  <textarea name="d" placeholder="Copy DOI here"></textarea>
  <input type="submit" value="Submit">
  </form>
  <br/>
  <?php

  $d = $_GET[d];
  print 'd = '.$d.'<br/>';
  $a = preg_split("/[\r\s]+/", $d);
  $count = count($a);
  print 'count = '.$count.'<br/>';
  print '<table>
  <tr><th class="left">DOI</th><th class="right">Registration Agency</th></tr>
  ';
  for($i=0;$i<$count;$i++) {
    print 'a['.$i.'] = '.$a[$i].'<br/>';
    $url = 'https://api.crossref.org/works/'.$a[$i].'/agency';
    $c = curl_init();
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_URL, $url);
    $curl = curl_exec($c);
    curl_close($c);
    $doi = json_decode($curl, true);
    print
    '<tr><td>'.$a[$i].'</td><td>'.$doi["message"]["agency"]["label"].
    '</td></tr>';
  }
  print '</table>';
  ?>

  <hr/>
  <i>DraID by grolimur - v0.1 - April 5, 2018</i><br/>
  released under MIT License on <a href="https://github.com/grolimur/DraID" target="_blank">Github</a>
</body>
</html>
