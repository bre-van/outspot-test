<?php
require_once __DIR__ . "/include/config.php";
require_once __DIR__ . "/vendor/autoload.php";

include 'include/header.php';

use Mollie\Api\MollieApiClient;

$hash_check = false;

try {
    $mollie = new MollieApiClient();
    $mollie->setApiKey(MOLLIE_API_KEY);
    if (isset($_SESSION['payment_hash']) && isset($_GET['hash']) && isset($_SESSION['payment_id'])) {
        if ($_SESSION['payment_hash'] == $_GET['hash']) {
            $hash_check = true;
            $payment = $mollie->payments->get($_SESSION['payment_id']);
        }
    }
} catch (\Mollie\Api\Exceptions\ApiException $e) {
    /* Mollie API errors, wouldn't display these on screen normally */
    ?>
    <div class="alert alert-danger">
        <?php
        echo $e;
        ?>
    </div>
    <?php
}
?>
    <main class="form-payment">
        <form role="form" method="post" action="process.php" id="paymentForm">
            <h1 class="h3 mb-3 fw-normal">Outspot test - Brecht</h1>
            <?php
            if ($hash_check && isset($payment)) {
                if ($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargebacks()) {
                    ?>
                    <div class="alert alert-success">
                        Thank you for your payment!
                    </div>
                    <a href="index.php" class="btn btn-primary">
                        Make new payment!
                    </a>
                    <?php
                } elseif ($payment->isOpen()) {
                    ?>
                    <div class="alert alert-info">
                        Your payment is still open
                    </div>
                    <?php
                } elseif ($payment->isPending()) {
                    ?>
                    <div class="alert alert-info">
                        Your payment is pending
                    </div>
                    <?php
                } elseif ($payment->isFailed()) {
                    ?>
                    <div class="alert alert-danger">
                        Your payment failed
                    </div>
                    <?php
                } elseif ($payment->isExpired()) {
                    ?>
                    <div class="alert alert-warning">
                        Your payment has expired
                    </div>
                    <?php
                } elseif ($payment->isCanceled()) {
                    ?>
                    <div class="alert alert-warning">
                        Your payment is canceled
                    </div>
                    <?php
                }
            } else {
                if (!$hash_check) {
                    ?>
                    <div class="alert alert-danger">
                        Your payment is not recognized
                    </div>
                    <?php
                }
            }
            ?>
        </form>
    </main>
<?php
require_once 'include/footer.php';
?>