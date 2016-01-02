<?php

    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // render form
        render("../templates/buy.php", ["title" => "Buy"]);
    }
        
    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // verify presence of symbol and desired number of shares  
        if (empty($_POST["symbol"]))
        {                                     
            apologize("Please name the stock you want to buy");
        }

        else if (empty($_POST["shares"]) || preg_match("/^\d+$/", $_POST["shares"] === false))
        {
            apologize("Please give a positive number of shares.");
        }
                                                                                                                                                    
        else
	    {
	 	    // make sure symbol is valid
	 	    $symbup = strtoupper($_POST["symbol"]);
	 		 	
     		if ($symbol = lookup($symbup) === false)
     		{
     			apologize("This is not a valid stock symbol. Try again!");
     		}
     		
     		// compare available cash and cost of transaction
     		else
     		{
     		    $stock = lookup($symbup);
     		    $symbol = $stock["symbol"];
     			$id = $_SESSION["id"];
	     		$cash = query("SELECT cash FROM users WHERE id = $id");	     		
	     		$shares = $_POST["shares"];
	     		$price = $stock["price"];
	     		$cost = $price * $shares;
	     		
	     		// verify sufficient cash
     			if($cost > $cash[0]["cash"])
		     	{
		     		apologize("You have insufficient cash to buy this number of the desired shares. Please modify your buying order!");
		     	}
		     	
		     	else
		     	{
		     	    // process the acquisition
     		        query("INSERT INTO portfolio (id, symbol, shares) VALUES($id, '$symbol', $shares) 
     		        ON DUPLICATE KEY UPDATE shares = shares + $shares");
		 		    query("UPDATE users SET cash = cash - $cost WHERE id = $id");
		 		    query("INSERT INTO history (uid, symbol, shares, price, trans) VALUES ($id, '$symbol', $shares, $price, 'BUY')");
		 		    
		 		    // confirm the acquisition
		 		    $mail = query("SELECT mail FROM users WHERE id = $id");
		 		    $to = $mail[0]["mail"];
                    $subject = "Stock buying confirmation";
                    $msg = "You bought " . $shares . " shares of " . $symbol . " at a price of " . $price . " per share";
                    mail($to, $subject, $msg);
		 		    render("../templates/buying_confirmation.php", ["title" => "Confirm Acquisition", "stock" => $stock, "shares" => $shares]);
		 	    }
	 	    }
	    }
    }
    
    else
    {
        // else render form
        render("../templates/buy.php");
    }
