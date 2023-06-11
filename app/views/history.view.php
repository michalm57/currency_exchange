<?php require 'partials/head.php';
?>
<div class="table-container justify-content-center">
    <?php
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        unset($_SESSION['alert']);

        echo '<div class="alert alert-' . $alert['type'] . '">' . $alert['message'] . '</div>';
    }
    ?>
    <table id="exchange-history-table">
        <thead>
            <tr>
                <th>Source</th>
                <th>Target</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php $index = 0; ?>
            <?php foreach ($historyRecords as $record) : ?>
                <tr <?php (isset($exchangeRedirect) && $exchangeRedirect && $index === 0) ? print 'class="first-row"' : '' ?>>
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
                <?php $index++; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require 'partials/footer.php'; ?>