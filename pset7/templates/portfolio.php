<ul class="nav nav-pills">
    <li><a href="index.php">Portfolio</a></li>
    <li><a href="quote.php">Quote</a></li>
    <li><a href="buy.php">Buy</a></li>
    <li><a href="sell.php">Sell</a></li>
    <li><a href="history.php">History</a></li>    
    <li><a href="logout.php"><strong>Log Out</strong></a></li>
</ul>


<table class="table table-striped">

    <thead>
        <tr>
            <th>Symbol</th>
            <th>Name</th>
            <th>Shares</th>
            <th>Price</th>
            <th>Value</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($positions as $position): ?>	
        <tr style="text-align:left">   
            <td><?= $position["symbol"] ?></td>
            <td><?= $position["name"] ?></td>
            <td><?= $position["shares"] ?></td>
            <td><?= number_format($position["price"], 4) ?></td>
            <td><?= number_format($position["value"], 2) ?></td>
        </tr>
    <?php endforeach ?>
    
    <tr>
        <td colspan="4">CASH</td>
        <td>$<?= number_format($cash[0]["cash"], 2) ?></td>
    </tr>

    </tbody>

</table>

