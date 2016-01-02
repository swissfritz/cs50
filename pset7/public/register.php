<?php

    // configuration
    require("../includes/config.php");
    
     // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // render form
        render("register.php", ["title" => "Register"]);
    }
       
    // if user reached page via POST
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["username"]))
        {
            apologize("You must provide your username.");
        }
        else if (empty($_POST["password"]))
        {
            apologize("You must provide your password.");
        }
        else if (empty($_POST["confirmation"]))
        {
            apologize("You must confirm your password.");
        }
        else if ($_POST["password"] !== $_POST["confirmation"])
        {
            apologize("Password and Confirmation must be identical.");
        }
           
        // insert user into database
        $result = query("INSERT INTO users (username, hash, cash) VALUES (?, ?, 10.000)", $_POST["username"], crypt($_POST["password"]));
           
        // if query returns false
        if ($result === false)
        {
            // duplicate user
            $check = query("SELECT id FROM users WHERE username = ?", $_POST["username"]);
            if ($check !== false)
            {
                apologize("Duplicate username. Register with a different username!");
            }
            
            // unknown error
            else
            {
                apologize("Unknown error - registration failed!");
            }
        }
            
        //if registration succeded, log user in and redirect to index.php
        else
        {
            $rows = query("SELECT LAST_INSERT_ID() AS id");
            $id = $rows[0]["id"];
            $_SESSION["id"] = $id;
            redirect("/");
        }
    }
    
    else
    {
        // render form
        render("register.php", ["title" => "Register"]);
    }
?>
