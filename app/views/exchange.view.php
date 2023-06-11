<?php require 'partials/head.php'; ?>
<?php
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        unset($_SESSION['alert']);

        echo '<div class="alert alert-' . $alert['type'] . '">' . $alert['message'] . '</div>';
    }
?>
<div class="justify-content-center d-flex">
    <form method="POST" action="/exchange/calculate-exchange" class="form form-container">
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" required class="form-control" min="1" step="any" placeholder="Amount">
        </div>

        <div class="form-group">
            <label for="source_currency_id">Source Currency:</label>
            <select id="source_currency_id" name="source_currency_id" required class="form-control">
                <option value="" disabled selected hidden>Source Currency...</option>
                <?php
                foreach ($codesValues as $id => $currencyCode) {
                    echo "<option value='$id'>$currencyCode</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="target_currency_id">Target Currency:</label>
            <select id="target_currency_id" name="target_currency_id" required class="form-control">
                <option value="" disabled selected hidden>Target Currency...</option>
                <?php
                foreach ($codesValues as $id => $currencyCode) {
                    echo "<option value='$id'>$currencyCode</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Calculate</button>
    </form>
</div>
<?php require 'partials/footer.php'; ?>