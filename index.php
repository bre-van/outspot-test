<?php
include 'include/header.php';

$errors = [];
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
}
?>
    <main class="form-payment">
        <form role="form" method="post" action="process.php" id="paymentForm">
            <h1 class="h3 mb-3 fw-normal">Outspot test - Brecht</h1>

            <div class="form-floating">
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">€</span>
                    </div>
                    <input type="number" class="form-control" id="payment" name="payment"
                           placeholder="0.00" min="10" max="100" step="0.01" value="10.00"
                            required>
                </div>
                <p>
                    Enter an amount between 10 and 100 EUR
                </p>
                <?php
                foreach ($errors as $error) {
                    ?>
                    <div class="alert alert-danger">
                        <b><?= $error ?></b>
                    </div>
                    <?php
                }
                ?>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">Pay</button>
        </form>
    </main>
<?php
require_once 'include/footer.php';
?>