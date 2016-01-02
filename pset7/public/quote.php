<?php

    // configuration
    require("../includes/config.php");
    
    // if user reached page via GET (as by clicking a link or via redirect)
    if
     ($_SERVER["REQUEST_METHOD"] == "GET")
        {
            // render form
            render("quote.php", ["title" => "Quote"]);
        }
        
    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["symbol"]))
        {
            apologize("You must provide a stock symbol.");
        }
        else
        {
            $stock = lookup($_POST["symbol"]);
            if ($stock === false)
            {
                apologize("This is not a valid stock symbol. Try again!");
            }
            else
            {
                render("quote_show.php", ["title" => "Show Quote", "symbol" => $stock["symbol"], "name" => $stock["name"], "price" => (float)number_format($stock["price"], 4)]);
            }
        }
    }
?>

