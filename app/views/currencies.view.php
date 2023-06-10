<?php require 'partials/head.php';?>
<title>Currencies</title>
<table id="currencies-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Code</th>
                <th>Rate</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($currencies as $currency): ?>
                <tr>
                    <td><?= $currency->name; ?></td>
                    <td><?= $currency->code; ?></td>
                    <td><?= $currency->rate; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php require 'partials/footer.php';?>
