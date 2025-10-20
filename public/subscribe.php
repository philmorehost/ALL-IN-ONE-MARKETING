<?php
require_once '../app/functions.php';
if (!is_logged_in()) {
    redirect('login.php');
}

$plan = null;
if (isset($_GET['plan_id'])) {
    $stmt = pdo()->prepare("SELECT * FROM plans WHERE id = ?");
    $stmt->execute([$_GET['plan_id']]);
    $plan = $stmt->fetch();
}

if (!$plan) {
    redirect('dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card w-75 mx-auto">
            <div class="card-header">
                <h1>Subscribe to <?php echo htmlspecialchars($plan['name']); ?></h1>
            </div>
            <div class="card-body">
                <p>You are about to subscribe to the <strong><?php echo htmlspecialchars($plan['name']); ?></strong> plan for <strong>$<?php echo htmlspecialchars($plan['price']); ?></strong>.</p>

                <div class="row">
                    <div class="col-md-6">
                        <h4>Pay with Paystack</h4>
                        <p>Click the button below to pay securely with Paystack.</p>
                        <!-- Paystack button will go here -->
                        <button class="btn btn-primary" disabled>Pay with Paystack (Coming Soon)</button>
                    </div>
                    <div class="col-md-6">
                        <h4>Manual Bank Transfer</h4>
                        <p>Please transfer the exact amount to the following bank account:</p>
                        <ul>
                            <li><strong>Bank Name:</strong> GTBank</li>
                            <li><strong>Account Number:</strong> 0123456789</li>
                            <li><strong>Account Name:</strong> All-in-One Digital</li>
                        </ul>
                        <p>After making the transfer, please upload your proof of payment below.</p>
                        <?php require_once '../app/csrf.php'; ?>
                        <form action="../app/manual_payment.php" method="post" enctype="multipart/form-data">
                            <?php echo csrf_input(); ?>
                            <input type="hidden" name="plan_id" value="<?php echo $plan['id']; ?>">
                            <div class="mb-3">
                                <label for="proof" class="form-label">Proof of Payment</label>
                                <input type="file" class="form-control" id="proof" name="proof" required>
                            </div>
                            <button type="submit" class="btn btn-secondary">Submit Proof of Payment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
