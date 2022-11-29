<?php
require_once ('connect.php');
if(isSet($_REQUEST['punkt'])){
    global $yhendus;
    $kask=$yhendus->prepare('
UPDATE tantsud SET punktid=punktid+1 WHERE id=?');
    $kask->bind_param("s", $_REQUEST['punkt']);
    $kask->execute();
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>TARpv21 tantsud</title>
</head>
<body>
<h1>Tantud TARpv21</h1>
<table>
    <tr>
        <th>
            Tantsupaar
        </th>
        <th>
            Punktid
        </th>
        <th>
            Haldus
        </th>
    </tr>

    <?php
    // tabeli sisu näitamine
    global $yhendus;
    $kask=$yhendus->prepare('
SELECT id, tantsupaar, punktid FROM tantsud');
    $kask->bind_result($id, $tantsupaar, $punktid);
    $kask->execute();
    while($kask->fetch()){
        echo  "<tr>";
        echo "<td>". $tantsupaar."</td>";
        echo "<td>". $punktid."</td>";
        echo "<td><a href='?punkt=$id'>Lisa 1punkt</a></td>";
        echo "</tr>";
    }
    ?>
</table>
</body>