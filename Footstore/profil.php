<?php
// Start the session
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Include the database connection file
require_once 'pdo.php';

// Fetch the current user's data
$user_id = $_SESSION['user']['user_id'];
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Initialize variables
$username = $user['username'];
$email = $user['email'];
$first_name = $user['first_name'];
$last_name = $user['last_name'];
$errors = [];

// Handle profile update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    // Sanitize and validate input
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);

    // Validate username
    if (empty($username)) {
        $errors['username'] = 'Username is required.';
    } elseif (strlen($username) < 3) {
        $errors['username'] = 'Username must be at least 3 characters long.';
    }

    // Validate email
    if (empty($email)) {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format.';
    } else {
        // Check if email is already taken by another user
        $sql = "SELECT * FROM users WHERE email = ? AND user_id != ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email, $user_id]);
        if ($stmt->fetch()) {
            $errors['email'] = 'Email is already registered.';
        }
    }

    // If no errors, update the profile
    if (empty($errors)) {
        $sql = "UPDATE users SET username = ?, email = ?, first_name = ?, last_name = ? WHERE user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email, $first_name, $last_name, $user_id]);

        // Update session data
        $_SESSION['user']['username'] = $username;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['first_name'] = $first_name;
        $_SESSION['user']['last_name'] = $last_name;

        // Display success message
        $success_message = 'Profile updated successfully!';
    }
}

// Handle password update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate current password
    if (empty($current_password)) {
        $errors['current_password'] = 'Current password is required.';
    } elseif (!password_verify($current_password, $user['password_hash'])) {
        $errors['current_password'] = 'Current password is incorrect.';
    }

    // Validate new password
    if (empty($new_password)) {
        $errors['new_password'] = 'New password is required.';
    } elseif (strlen($new_password) < 6) {
        $errors['new_password'] = 'Password must be at least 6 characters long.';
    }

    // Validate confirm password
    if (empty($confirm_password)) {
        $errors['confirm_password'] = 'Please confirm your password.';
    } elseif ($new_password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    // If no errors, update the password
    if (empty($errors)) {
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password_hash = ? WHERE user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$password_hash, $user_id]);

        // Display success message
        $success_message = 'Password updated successfully!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile - Footcap</title>
    <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml" />
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet" />
    <style>
        body {
            background: var(--cultured);
            padding-top: 90px;
            margin: 0;
        }

        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .profile-container h2 {
            font-size: 2.4rem;
            color: var(--rich-black-fogra-29);
            margin-bottom: 20px;
        }

        .profile-section {
            background: var(--white);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .profile-section h3 {
            font-size: 1.8rem;
            color: var(--rich-black-fogra-29);
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 1.4rem;
            color: var(--onyx);
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 1.4rem;
            border: 1px solid var(--gainsboro);
            border-radius: 5px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            border-color: var(--bittersweet);
            outline: none;
        }

        .btn-primary {
            background: var(--bittersweet);
            color: var(--white);
            padding: 10px 20px;
            font-size: 1.4rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--salmon);
        }

        .errors {
            color: var(--bittersweet);
            margin-bottom: 15px;
        }

        .errors ul {
            list-style: none;
            padding: 0;
        }

        .errors li {
            margin-bottom: 5px;
        }

        .success-message {
            color: green;
            margin-bottom: 15px;
        }
        .profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--white);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            z-index: 1;
            min-width: 150px;
        }

        .dropdown-content a {
            display: block;
            padding: 10px 15px;
            color: var(--rich-black-fogra-29);
            text-decoration: none;
            font-size: 1.4rem;
            transition: background 0.3s ease;
        }

        .dropdown-content a:hover {
            background: var(--cultured);
        }

        /* Show dropdown on hover */
        .profile-dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>

<body>
    <header class="header" data-header>
    <div class="container">
            <div class="overlay" data-overlay></div>

            <a href="index.php" class="logo">
                <img
                    src="./assets/images/logo.svg"
                    width="160"
                    height="50"
                    alt="Footcap logo" />
            </a>

            <button class="nav-open-btn" data-nav-open-btn aria-label="Open Menu">
                <ion-icon name="menu-outline"></ion-icon>
            </button>

            <nav class="navbar" data-navbar>
                <button
                    class="nav-close-btn"
                    data-nav-close-btn
                    aria-label="Close Menu">
                    <ion-icon name="close-outline"></ion-icon>
                </button>

                <a href="index.php" class="logo">
                    <img
                        src="./assets/images/logo.svg"
                        width="190"
                        height="50"
                        alt="Footcap logo" />
                </a>

                <ul class="navbar-list">
                    <li class="navbar-item">
                        <a href="index.php" class="navbar-link">Home</a>
                    </li>

                    <li class="navbar-item">
                        <a href="about.php" class="navbar-link">About</a>
                    </li>

                    <li class="navbar-item">
                        <a href="products.php" class="navbar-link">Products</a>
                    </li>

                    <li class="navbar-item">
                        <a href="shop.php" class="navbar-link">Shop</a>
                    </li>

                    <li class="navbar-item">
                        <a href="blog.php" class="navbar-link">Blog</a>
                    </li>

                    <li class="navbar-item">
                        <a href="contact.php" class="navbar-link">Contact</a>
                    </li>
                </ul>

                <ul class="nav-action-list">
                    <li>
                        <button class="nav-action-btn" onclick="window.location.href='search.php';">
                            <ion-icon name="search-outline" aria-hidden="true"></ion-icon>
                            <span class="nav-action-text">Search</span>
                        </button>
                    </li>

                    <li class="profile-dropdown">
                        <a href="profil.php" class="nav-action-btn">
                            <ion-icon name="person-outline" aria-hidden="true"></ion-icon>
                            <span class="nav-action-text">Profile</span>
                        </a>
                        <div class="dropdown-content">
                            <a href="profil.php">Profile</a>
                            <a href="logout.php">Logout</a>
                        </div>
                    </li>

                    <li>
                        <button class="nav-action-btn">
                            <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
                            <span class="nav-action-text">Wishlist</span>
                            <data class="nav-action-badge" value="<?php echo isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0; ?>" aria-hidden="true">
                                <?php echo isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0; ?>
                            </data>
                        </button>
                    </li>
                    <li>
                        <a href="bag.php" class="nav-action-btn">
                            <ion-icon name="bag-outline" aria-hidden="true"></ion-icon>
                            <span class="nav-action-text">Bag</span>
                            <data class="nav-action-badge" value="<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>" aria-hidden="true">
                                <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                            </data>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="profile-container">
            <h2>Profile</h2>

            <!-- Display success message -->
            <?php if (isset($success_message)): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <!-- Display errors -->
            <?php if (!empty($errors)): ?>
                <div class="errors">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Profile Update Section -->
            <div class="profile-section">
                <h3>Update Profile</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="<?php echo htmlspecialchars($username); ?>"
                            required />
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?php echo htmlspecialchars($email); ?>"
                            required />
                    </div>

                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            value="<?php echo htmlspecialchars($first_name); ?>"
                            required />
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            value="<?php echo htmlspecialchars($last_name); ?>"
                            required />
                    </div>

                    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                </form>
            </div>

            <!-- Password Update Section -->
            <div class="profile-section">
                <h3>Change Password</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            required />
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input
                            type="password"
                            id="new_password"
                            name="new_password"
                            required />
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            required />
                    </div>

                    <button type="submit" name="update_password" class="btn btn-primary">Change Password</button>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-top section">
            <div class="container">
                <div class="footer-brand">
                    <a href="index.html" class="logo">
                        <img
                            src="./assets/images/logo.svg"
                            width="160"
                            height="50"
                            alt="Footcap logo" />
                    </a>

                    <ul class="social-list">
                        <li>
                            <a href="#" class="social-link">
                                <ion-icon name="logo-facebook"></ion-icon>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="social-link">
                                <ion-icon name="logo-twitter"></ion-icon>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="social-link">
                                <ion-icon name="logo-pinterest"></ion-icon>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="social-link">
                                <ion-icon name="logo-linkedin"></ion-icon>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="footer-link-box">
                    <ul class="footer-list">
                        <li>
                            <p class="footer-list-title">Contact Us</p>
                        </li>

                        <li>
                            <address class="footer-link">
                                <ion-icon name="location"></ion-icon>
                                <span class="footer-link-text">
                                    2751 S Parker Rd, Aurora, CO 80014, United States
                                </span>
                            </address>
                        </li>

                        <li>
                            <a href="tel:+557343673257" class="footer-link">
                                <ion-icon name="call"></ion-icon>
                                <span class="footer-link-text">+557343673257</span>
                            </a>
                        </li>

                        <li>
                            <a href="mailto:footcap@help.com" class="footer-link">
                                <ion-icon name="mail"></ion-icon>
                                <span class="footer-link-text">footcap@help.com</span>
                            </a>
                        </li>
                    </ul>

                    <ul class="footer-list">
                        <li>
                            <p class="footer-list-title">My Account</p>
                        </li>

                        <li>
                            <a href="#" class="footer-link">
                                <ion-icon name="chevron-forward-outline"></ion-icon>
                                <span class="footer-link-text">My Account</span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="footer-link">
                                <ion-icon name="chevron-forward-outline"></ion-icon>
                                <span class="footer-link-text">View Cart</span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="footer-link">
                                <ion-icon name="chevron-forward-outline"></ion-icon>
                                <span class="footer-link-text">Wishlist</span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="footer-link">
                                <ion-icon name="chevron-forward-outline"></ion-icon>
                                <span class="footer-link-text">Compare</span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="footer-link">
                                <ion-icon name="chevron-forward-outline"></ion-icon>
                                <span class="footer-link-text">New Products</span>
                            </a>
                        </li>
                    </ul>

                    <div class="footer-list">
                        <p class="footer-list-title">Opening Time</p>

                        <table class="footer-table">
                            <tbody>
                                <tr class="table-row">
                                    <th class="table-head" scope="row">Mon - Tue:</th>
                                    <td class="table-data">8AM - 10PM</td>
                                </tr>

                                <tr class="table-row">
                                    <th class="table-head" scope="row">Wed:</th>
                                    <td class="table-data">8AM - 7PM</td>
                                </tr>

                                <tr class="table-row">
                                    <th class="table-head" scope="row">Fri:</th>
                                    <td class="table-data">7AM - 12PM</td>
                                </tr>

                                <tr class="table-row">
                                    <th class="table-head" scope="row">Sun:</th>
                                    <td class="table-data   ">Closed</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <p class="copyright">
                    &copy; 2023 Footcap. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Ionicons -->
    <script
        type="module"
        src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script
        nomodule
        src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>