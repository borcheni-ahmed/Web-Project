<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once 'pdo.php';

if (!isset($_GET['order_id'])) {
    header('Location: bag.php');
    exit;
}

$order_id = $_GET['order_id'];

// Fetch the order details
$sql = "SELECT * FROM orders WHERE order_id = :order_id AND user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':order_id' => $order_id,
    ':user_id' => $_SESSION['user']['user_id']
]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: bag.php');
    exit;
}

// Fetch the cart items from the session
$cart_items = $_SESSION['cart'];
$total_amount = 0;

foreach ($cart_items as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice - Footcap</title>
    <link rel="stylesheet" href="./assets/css/style.css" />
    <style>
        body {
            background: var(--cultured);
            padding-top: 90px;
            margin: 0;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: var(--white);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-header h2 {
            font-size: 2.4rem;
            color: var(--rich-black-fogra-29);
            margin-bottom: 10px;
        }

        .invoice-header p {
            font-size: 1.2rem;
            color: var(--onyx);
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .invoice-table th,
        .invoice-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--gainsboro);
        }

        .invoice-table th {
            background: var(--cultured);
            font-weight: var(--fw-600);
        }

        .invoice-table td img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }

        .total-price {
            font-size: 1.8rem;
            font-weight: var(--fw-600);
            text-align: right;
            margin-top: 20px;
        }

        .print-btn {
            display: block;
            width: 100%;
            max-width: 200px;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 1.4rem;
            background: var(--bittersweet);
            color: var(--white);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
            text-align: center;
        }

        .print-btn:hover {
            background: var(--salmon);
        }

        @media print {
            body {
                padding-top: 0;
            }

            .invoice-container {
                box-shadow: none;
                border-radius: 0;
            }

            .print-btn {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Main Content -->
    <main>
        <div class="invoice-container">
            <div class="invoice-header">
                <h2>Invoice for Order #<?php echo $order['order_id']; ?></h2>
                <p>Date: <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>

            <table class="invoice-table">
                <thead>
                    <tr>
                        <th style="width: 200px;">Product</th>
                        <th style="width: 90px;">Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td>
                                <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>" />
                                <br>
                                <span><?php echo $item['name']; ?></span>
                            </td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-price">
                Total: $<?php echo number_format($total_amount, 2); ?>
            </div>

            <button class="print-btn" onclick="window.print()">Print Invoice</button>
        </div>
    </main>

    <footer class="footer">
    </footer>

    <script src="./assets/js/script.js"></script>
</body>

</html>