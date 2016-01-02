<?php

    // configuration
    require("../includes/config.php");
    
    // if user reached page via GET
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // render form
        render("../templates/sell.php", ["title" => "Sell"]);
    }
        
    // if user reached page via POST
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {	
	    // make sure request is valid
	 	$symbup = strtoupper($_POST["symbol"]);
	 	
	    if (empty($_POST["symbol"]))
	    {
		    apologize("No stock identified!");
	    }
	    
	    else if ($symbol = lookup($symbup) === false)
     		{
     			apologize("This is not a valid stock symbol. Try again!");
     		}
	    
	    else
	    {
		    $id = $_SESSION["id"];
		    $symbol = strtoupper($_POST["symbol"]);
		    
	    // check if the shares to sell are available
	    if (!$shares = query("SELECT shares FROM portfolio WHERE id = $id AND symbol = '$symbol'"))
	    {
		    apologize("You don't own any shares of this stock");
	    }
	    
	    else
	    {
	     	$value = lookup("$symbol");
	     	$shares = $shares[0]["shares"];
	     	$price = $value["price"];
	     	$proceeds = $shares*$price;
	     	
	        // process the sale
	     	query("DELETE FROM portfolio WHERE id = $id AND symbol = '$symbol'");
	     	query("UPDATE users SET cash = cash + $proceeds WHERE id = $id");
	     	query("INSERT INTO history (uid, symbol, trans, shares, price) 
	     	VALUES($id, '$symbol', 'SELL', $shares, $price)");
	     	
	     	// confirm the sale
		    $mail = query("SELECT mail FROM users WHERE id = $id");
		    $to = $mail[0]["mail"];
            $subject = "Stock selling confirmation";
            $msg = "You sold " . $shares . " shares of " . $symbol . " at a price of " . $price . " per share";
            mail($to, $subject, $msg);
	     	render("../templates/selling_confirmation.php", ["title" => "Sell", "value" => $value]);
	     }
	    }
    }
    
    else
    {
      // else render form
      render("../templates/sell.php", ["title" => "Sell"]);
    }
    
    ?>
