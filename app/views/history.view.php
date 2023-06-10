<?php require 'partials/head.php'; ?>
<title>Currencies</title>
<div class="table-container justify-content-center">
    <table id="exchange-history-table">
        <thead>
            <tr>
                <th>Sorce</th>
                <th>Target</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($historyRecords as $record) : ?>
                <tr>
                    <td>
                        <?= $record->amount . ' ' . $record->source_currency; ?>
                    </td>
                    <td>
                        <?= $record->amount_after_conversion . ' ' . $record->target_currency; ?><br>
                    </td>
                    <td>
                        <?= $record->created_at; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require 'partials/footer.php'; ?>