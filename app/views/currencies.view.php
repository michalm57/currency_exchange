<?php require 'partials/head.php'; ?>
<div class="table-container justify-content-center">
    <table id="currencies-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Code</th>
                <th>Rate</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($currencies as $currency) : ?>
                <tr>
                    <td><?= ucfirst($currency->name); ?></td>
                    <td><?= $currency->code; ?></td>
                    <td><?= $currency->rate; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require 'partials/footer.php'; ?>