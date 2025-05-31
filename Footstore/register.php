<?php
// Start the session
session_start();

// Include the database connection file
require_once 'pdo.php';

// Initialize variables
$username = $email = $password = $confirm_password = $first_name = $last_name = '';
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
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
        // Check if email already exists
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors['email'] = 'Email is already registered.';
        }
    }

    // Validate password
    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters long.';
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', $password)) {
        $errors['password'] = 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.';
    }

    // Validate confirm password
    if (empty($confirm_password)) {
        $errors['confirm_password'] = 'Please confirm your password.';
    } elseif ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    // Validate first name
    if (empty($first_name)) {
        $errors['first_name'] = 'First name is required.';
    }

    // Validate last name
    if (empty($last_name)) {
        $errors['last_name'] = 'Last name is required.';
    }

    // If no errors, insert user into the database
    if (empty($errors)) {
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Insert user into the database
            $sql = "INSERT INTO users (username, email, password_hash, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $email, $password_hash, $first_name, $last_name, 'user']); // Default role is 'user'

            // Fetch the newly created user
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            // Add user to session
            $_SESSION['user'] = [
                'user_id' => $user['user_id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'role' => $user['role']
            ];

            // Redirect to index.html
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $errors['database'] = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Footcap</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet" />
    <style>
        .signup-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: var(--white);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .signup-container h2 {
            font-size: 2rem;
            color: var(--rich-black-fogra-29);
            margin-bottom: 20px;
        }

        /* Errors */
        .errors {
            color: var(--bittersweet);
            margin-bottom: 20px;
        }

        .errors ul {
            list-style: none;
            padding: 0;
        }

        .errors li {
            margin-bottom: 5px;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
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

        /* Button */
        .btn-primary {
            width: 100%;
            padding: 12px;
            font-size: 1.6rem;
            background: var(--bittersweet);
            color: var(--white);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--salmon);
        }

        /* Login Text */
        .login-text {
            font-size: 1.4rem;
            color: var(--onyx);
            margin-top: 20px;
        }

        .login-text a {
            color: var(--bittersweet);
            font-weight: var(--fw-600);
            text-decoration: none;
        }

        .login-text a:hover {
            font-family: var(--ff-josefin-sans);
            text-decoration: underline;
        }
    </style>
</head>
<body style="background: var(--cultured);">
    <div class="signup-container">
        <h2>Sign Up</h2>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST">
            <!-- First Name -->
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input
                    type="text"
                    id="first_name"
                    name="first_name"
                    value="<?php echo htmlspecialchars($first_name); ?>"
                    required />
            </div>

            <!-- Last Name -->
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input
                    type="text"
                    id="last_name"
                    name="last_name"
                    value="<?php echo htmlspecialchars($last_name); ?>"
                    required />
            </div>

            <!-- Username -->
            <div class="form-group">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="<?php echo htmlspecialchars($username); ?>"
                    required />
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?php echo htmlspecialchars($email); ?>"
                    required />
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required />
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    required />
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Sign Up</button>
            </div>

            <!-- Login Link -->
            <p class="login-text">
                Already have an account? <a href="login.php">Login</a>
            </p>
        </form>
    </div>
</body>
</html>