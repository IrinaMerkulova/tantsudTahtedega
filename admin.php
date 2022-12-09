<?php
require_once ('connect.php');
session_start();
if (!isset($_SESSION['tuvastamine'])) {
    header('Location: ab_login.php');
    exit();
}
//punktid nulliks
if(isSet($_REQUEST['punkt0'])){
    global $yhendus;
    $kask=$yhendus->prepare('
UPDATE tantsud SET punktid=0 WHERE id=?');
    $kask->bind_param("s", $_REQUEST['punkt0']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
//peitmine
if(isSet($_REQUEST['peitmine'])){
        $kask=$yhendus->prepare('
UPDATE tantsud SET avalik=0 WHERE id=?');
    $kask->bind_param("i", $_REQUEST['peitmine']);
    $kask->execute();
    }
//nätamine
if(isSet($_REQUEST['naitamine'])){
    $kask=$yhendus->prepare('
UPDATE tantsud SET avalik=1 WHERE id=?');
    $kask->bind_param("i", $_REQUEST['naitamine']);
    $kask->execute();
}

?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>TARpv21 tantsud</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<header>
    <div>
        <?=$_SESSION['kasutaja']?> on sisse logitud
        <form action="logout.php" method="post">
            <input type="submit" value="Logi välja" name="logout">
        </form>
    </div>
    <h1>Tantsud TARpv21</h1>
    <h2>Administraatori leht</h2>
    <nav>
        <ul>
            <li><a href="tantsudePunktid.php">Kasutaja leht</a>
            <li><a href="admin.php">Admin leht</a>
        </ul>

    </nav>
</header>
<table>
    <tr>
        <th>
            Tantsupaar
        </th>
        <th>
            Punktid<br>
            Punktid nulliks
        </th>
        <th>Kommentaarid
            <br>Kustuta kommentaarid</th>
        <th>Avalikustamine staatus</th>
        <th>
           Avalikustamise päev
        </th>
    </tr>
<?php
// tabeli sisu näitamine
global $yhendus;
$kask=$yhendus->prepare('
SELECT id, tantsupaar, punktid, kommentaarid, avaliku_paev, avalik FROM tantsud');
$kask->bind_result($id, $tantsupaar, $punktid, $kommentaarid, $apaev, $avalik);
$kask->execute();
while($kask->fetch()) {
    $tekst='Näita';
    $seisund='naitamine';
    $kasutajatekst='Kasutaja ei näe';
    if($avalik==1){
        $tekst='Peida';
        $seisund='peitmine';
        $kasutajatekst='Kasutaja näeb';
    }
        echo "<tr>";
        echo "<td>" . $tantsupaar . "</td>";
        echo "<td>" . $punktid . "<br><a href='?punkt0=$id'>nulliks</a></td>";
        echo "<td>" . $kommentaarid . "</td>";
    echo "<td>$kasutajatekst<br>
                <a href='?$seisund=$id'>$tekst</a><br>         
           </td>";
        echo "<td>$apaev</td>";
    echo "<tr>";
}
?>
</table>
</body>
</html>