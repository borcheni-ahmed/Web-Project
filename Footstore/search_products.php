<?php
session_start();

require_once 'pdo.php';

// Get the search query and category ID from the request
$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';
$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Build the SQL query
$sql = "SELECT p.*, c.name AS category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.category_id 
        WHERE (p.name LIKE :query OR c.name LIKE :query)";
$params = [':query' => "%$searchQuery%"];

if ($categoryId > 0) {
    $sql .= " AND p.category_id = :category_id";
    $params[':category_id'] = $categoryId;
}

// Fetch products from the database
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Generate HTML for the search results
if (empty($products)) {
    echo '<div class="empty-cart">No products found.</div>';
} else {
    foreach ($products as $product): ?>
        <div class="product-card">
            <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" />
            <div class="card-content">
                <h3 class="card-title"><?php echo $product['name']; ?></h3>
                <p class="card-price">$<?php echo number_format($product['price'], 2); ?></p>
                <ul class="card-action-list">
                    <li class="card-action-item">
                        <button class="card-action-btn" aria-labelledby="card-label-1">
                            <ion-icon name="cart-outline"></ion-icon>
                        </button>
                        <div class="card-action-tooltip" id="card-label-1">Add to Cart</div>
                    </li>
                    <li class="card-action-item">
                        <button class="card-action-btn" aria-labelledby="card-label-2">
                            <ion-icon name="heart-outline"></ion-icon>
                        </button>
                        <div class="card-action-tooltip" id="card-label-2">Add to Wishlist</div>
                    </li>
                    <li class="card-action-item">
                        <button class="card-action-btn" aria-labelledby="card-label-3">
                            <ion-icon name="eye-outline"></ion-icon>
                        </button>
                        <div class="card-action-tooltip" id="card-label-3">Quick View</div>
                    </li>
                </ul>
            </div>
        </div>
    <?php endforeach;
}
?>