<?php
require_once ('connect.php');
// sessiooni algus
session_start();
if (!isset($_SESSION['tuvastamine'])) {
    header('Location: ab_login.php');
    exit();
}
//if($_SESSION['onAdmin']){

//}


// tagastab isAdmin session
function isAdmin(){
    return isset($_SESSION['onAdmin'])&&$_SESSION['onAdmin'];
}
//Uue tantsupaari lisamine
if (!empty($_REQUEST['paarinimi']) && isAdmin()) {
    global $yhendus;
    $kask=$yhendus->prepare("
    INSERT INTO tantsud (tantsupaar, avaliku_paev)
    VALUES (?, NOW())");
    $kask->bind_param("s",$_REQUEST['paarinimi']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}

// kommentaaride lisamine
if(isset($_REQUEST['uuskomment'])) {
    if (!empty($_REQUEST['komment'])) {
        global $yhendus;
        $kask = $yhendus->prepare("
UPDATE tantsud SET kommentaarid=CONCAT(kommentaarid, ?) 
               WHERE id=?");
        $kommentplus=$_REQUEST['komment']. "\n";
        $kask->bind_param("si", $kommentplus, $_REQUEST['uuskomment']);
        $kask->execute();
        header("Location: $_SERVER[PHP_SELF]");
    }
}
//punktide lisamine
if(isSet($_REQUEST['punkt'])){
        $kask=$yhendus->prepare('
UPDATE tantsud SET punktid=punktid+1 WHERE id=?');
    $kask->bind_param("s", $_REQUEST['punkt']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
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
    <h2>Kasutaja leht</h2>
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
            Punktid
        </th>
        <th>
            Haldus
        </th>
        <th>Kommentaarid</th>
    </tr>

    <?php
    // tabeli sisu näitamine
    global $yhendus;
    $kask=$yhendus->prepare('
SELECT id, tantsupaar, punktid, kommentaarid FROM tantsud Where avalik=1');
    $kask->bind_result($id, $tantsupaar, $punktid, $kommentaarid);
    $kask->execute();
    while($kask->fetch()){
        echo  "<tr>";
        echo "<td>". $tantsupaar."</td>";
        echo "<td>". $punktid."</td>";
        echo "<td><a href='?punkt=$id'>Lisa 1punkt</a></td>";
        $kommentaarid=nl2br(htmlspecialchars($kommentaarid));
        echo "<td>".$kommentaarid."</td>";

            echo "<td>

<form action='?'>
<input type='hidden' value='$id' name='uuskomment'>
<input type='text' name='komment'>
<input type='submit' value='OK'>
</form>
</td>";

        echo "</tr>";
    }
    ?>
</table>
<?php if(isAdmin()) { ?>
    <div>
    <h2 > Uue tantsupaari lisamine </h2 >
    <form action = "?" >
        <input type = "text" placeholder = "Tansupaari nimed" name = "paarinimi" >
        <input type = "submit" value = "OK" >
    </form >
</div >
  <?php  } ?>
</body>