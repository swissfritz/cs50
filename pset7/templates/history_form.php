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
            <th>Shares</th>
            <th>Price</th>
            <th>Transaction</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($history as $history): ?>	
        <tr style="text-align:left">   
            <td><?= $history["symbol"] ?></td>
            <td><?= $history["shares"] ?></td>
            <td><?= number_format($history["price"], 4) ?></td>
            <td><?= $history["trans"] ?></td>
            <td><?= $history["date"] ?></td>
        </tr>
    <?php endforeach ?>
    </tbody>

</table>
