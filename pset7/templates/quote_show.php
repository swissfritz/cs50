<div>
    <a href="/"><img alt="C$50 Finance" src="/img/logo.png"/></a>
</div>
<ul class="nav nav-pills">
    <li><a href="index.php">Portfolio</a></li>
    <li><a href="quote.php">Quote</a></li>
    <li><a href="buy.php">Buy</a></li>
    <li><a href="sell.php">Sell</a></li>
    <li><a href="history.php">History</a></li>  
    <li><a href="logout.php"><strong>Log Out</strong></a></li>
</ul>
<form>
    <fieldset>
        <div class="form-group">
            <p class="form-control"><?= htmlspecialchars($title) ?></p><br/>
            <p class="form-control"><?= htmlspecialchars($symbol) ?></p>
            <p class="form-control"><?= htmlspecialchars($name) ?></p>
            <p class="form-control"><?= htmlspecialchars($price) ?></p>
        </div>
    </fieldset>
</form>
