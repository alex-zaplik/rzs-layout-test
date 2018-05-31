<?php
    require_once 'parsedown/Parsedown.php';

    $servername = "localhost";
    $username = "root";
    $password = "";
    $databasename = "rzs";

    $conn = @new mysqli($servername, $username, $password, $databasename);
    if ($conn->connect_error) {
        echo "Database connection failed";
        exit();
    }

    $insert = $conn->prepare("UPDATE `articles` SET `Markdown` = ?, `HTML`= ? WHERE ID = ?");
    $insert->bind_param('ssi', $md, $html, $id); 

    $pd = new Parsedown();
    $md = $_POST["_text"];
    $html = $pd->text($md);
    $id = $_POST["_id"];
    $insert->execute();

    echo $html;

    $conn->close();
?>