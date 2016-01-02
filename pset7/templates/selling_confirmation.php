<ul class="nav nav-pills">
    <li><a href="index.php">Portfolio</a></li>
    <li><a href="quote.php">Quote</a></li>
    <li><a href="buy.php">Buy</a></li>
    <li><a href="sell.php">Sell</a></li>
    <li><a href="history.php">History</a></li>  
    <li><a href="logout.php"><strong>Log Out</strong></a></li>
</ul>
<?php
    // confirm the selling order
    print("You sold your shares of " . $value["name"] . " .");
	print("<br>");
    print("at ". $value["price"] . " dollars per share.");
    print("<br/>");
    print("Check your new cash balance by clicking 'Portfolio'!");
?>
