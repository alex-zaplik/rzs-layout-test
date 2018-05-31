<?php
    require_once 'parsedown/Parsedown.php';

    echo "<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>\n";

$FORM = <<<EOT
<form>
<textarea id="{{ IN }}">{{ MD }}</textarea>
<input type="button" onclick="Insert('{{ ID }}', '{{ IN }}', '{{ OUT }}')" value="Wyślij" />
</form>
EOT;

$SCRIPT = <<<EOT
<script>
    function Insert(id, input, output) {
        var dataJSON = {
            _text: $('#' + input).val(),
            _id: id
        };

        $.ajax({
            type :      'POST',
            url :       'db_refresh.php',
            data :      dataJSON,
            success :   function (data) {
                            $('#' + output).html(data);
                        },
            error :     function (data) {
                            console.log("Ajax error");
                        }
        });
    }
</script>
EOT;

    $servername = "localhost";
    $username = "root";
    $password = "";
    $databasename = "rzs";

    $pd = new Parsedown();

    $conn = @new mysqli($servername, $username, $password, $databasename);
    if ($conn->connect_error) {
        echo "Database connection failed";
        exit();
    }

    $sql = "SELECT * FROM articles";
    $result = $conn->query($sql);
    
    while($row = $result->fetch_assoc()) {
        $id = $row["ID"];
        $html = $row["HTML"];

        echo "<article>\n";
        echo "<h2>" . $row["Title"] . "</h2>\n";
        echo "<div id=\"text-" . $row["ID"] . "\">\n";
        if ($html != null) {
            echo $html . "\n";
        }
        echo "</div>\n</article>\n";

        echo str_replace(["{{ ID }}", "{{ IN }}", "{{ OUT }}", "{{ MD }}"], [$row["ID"], "md-" . $row["ID"], "text-" . $row["ID"], $row["Markdown"]], $FORM) . "\n";
    }

    $conn->close();

    echo $SCRIPT;
?>