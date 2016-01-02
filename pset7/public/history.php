<?php

    // configuration
    require("../includes/config.php");
    
    // get history from database
    $id = $_SESSION["id"];
    $history = query("SELECT * FROM history WHERE uid = $id ORDER BY date DESC");
    
    // load form
    render("history_form.php", ["title" => "History", "history" => $history]);

?>
