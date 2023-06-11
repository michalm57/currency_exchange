<head>
    <link rel="stylesheet" type="text/css" href="/public/css/style.css"></link>
</head>
<nav>
    <ul>
        <li class="nav-li"><a class="<?php echo ($_SERVER['REQUEST_URI'] == '/') ? 'active' : ''; ?>" href = "/">Home</a></li>
        <li class="nav-li"><a class="<?php echo ($_SERVER['REQUEST_URI'] == '/currencies') ? 'active' : ''; ?>" href = "/currencies">Currencies</a></li>
        <li class="nav-li"><a class="<?php echo ($_SERVER['REQUEST_URI'] == '/exchange' || $validationRedirect) ? 'active' : ''; ?>" href = "/exchange">Exchange</a></li>
        <li class="nav-li"><a class="<?php echo ($_SERVER['REQUEST_URI'] == '/history' || $exchangeRedirect) ? 'active' : ''; ?>" href = "/history">History</a></li>
    </ul>
</nav>
