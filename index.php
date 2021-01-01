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

  $dirtyDoiList = $_GET['d'];
  if(!empty($dirtyDoiList)) {
    // clean DOI list (removing empty lines)
    $cleanDoiList = preg_replace("/[\r\n]+/", " ", $dirtyDoiList);
    //$cleanDoiList = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", " ", $dirtyDoiList);
    // Split DOI list in an array to be processed
    $doiArray = preg_split("/[\r\s\n]+/", $cleanDoiList);
    $count = count($doiArray);
    print '<table>
    <tr><th class="left">DOI</th><th class="right">Registration Agency</th><th class="right">Owned by</th></tr>
    ';
    // process provided DOI and display related registration agency
    for($i=0;$i<$count;$i++) {
        if($doiArray[$i] !== " ") {
            $url2agency = 'https://api.crossref.org/works/'.$doiArray[$i].'/agency/?mailto:grolimur@nous4.ch';
            $c = curl_init();
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($c, CURLOPT_URL, $url2agency);
            $curl2agency = curl_exec($c);
            curl_close($c);
            $agency = json_decode($curl2agency, true);

            $url2memberId = 'https://api.crossref.org/works/'.$doiArray[$i].'?mailto:grolimur@nous4.ch';
            $mid = curl_init();
            curl_setopt($mid, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($mid, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($mid, CURLOPT_URL, $url2memberId);
            $curl2memberId = curl_exec($mid);
            curl_close($mid);
            $memberId = json_decode($curl2memberId, true);

            $url2member = 'https://api.crossref.org/members/'.$memberId["message"]["member"].'/works/?mailto:grolimur@nous4.ch';
            $m = curl_init();
            curl_setopt($m, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($m, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($m, CURLOPT_URL, $url2member);
            $curl2member = curl_exec($m);
            curl_close($m);
            $member = json_decode($curl2member, true);

            print
            '<tr><td>'.$doiArray[$i].'</td><td>'.$agency["message"]["agency"]["label"].'</td><td>'.$member["message"]["items"][0]["publisher"].
            '</td></tr>';
            }
        }
    print '</table>';
    }
  ?>

  <hr/>
  <i>DraID by grolimur - v0.1.3 - January 1st, 2021</i> [<a href="https://github.com/grolimur/DraID/blob/master/changelog.md" target="_blank">changelog</a>]<br/>
  released under MIT License on <a href="https://github.com/grolimur/DraID" target="_blank">Github</a>
</body>
</html>
