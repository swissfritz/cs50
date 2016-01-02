<?php
    // configuration
    require("../includes/config.php"); 
    
    // query user's portfolio       
    $rows = query("SELECT * FROM portfolio WHERE id = ?", $_SESSION["id"]); 
            
    // combine names and prices
    $positions = [];
    foreach ($rows as $row)
    {
        $stock = lookup($row["symbol"]);
        if ($stock !== false)
        {
            $positions[] = [
                "name" => $stock["name"],
                "price" => $stock["price"],
                "shares" => $row["shares"],
                "symbol" => $row["symbol"],
                "value" => $stock["price"] * $row["shares"]
            ];
         }
    }
    
    // query cash for template
    $cash = query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);
    
    // render portfolio (pass in new portfolio table and cash)
    render("portfolio.php", ["title" => "Portfolio", "positions" => $positions, "cash" => $cash]);
?>                                                                                      
