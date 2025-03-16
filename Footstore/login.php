<?php
session_start();

require_once 'pdo.php';

$email = $password = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email)) {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format.';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    }

    if (empty($errors)) {
        try {
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
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
            } else {
                $errors['login'] = 'Invalid email or password.';
            }
        } catch (PDOException $e) {
            $errors['database'] = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Footcap</title>
    <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml" />
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet" />
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: var(--cultured);
            margin: 0;
            padding: 0;
        }

        .login-container {
            background: var(--white);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-container h2 {
            font-size: 2.4rem;
            color: var(--rich-black-fogra-29);
            margin-bottom: 20px;
        }

        .login-form .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .login-form label {
            display: block;
            font-size: 1.4rem;
            color: var(--onyx);
            margin-bottom: 5px;
        }

        .login-form input {
            width: 100%;
            padding: 10px;
            font-size: 1.4rem;
            border: 1px solid var(--gainsboro);
            border-radius: 5px;
            transition: border-color 0.3s ease;
        }

        .login-form input:focus {
            border-color: var(--bittersweet);
            outline: none;
        }

        .login-form .btn {
            width: 100%;
            padding: 12px;
            font-size: 1.6rem;
            margin-top: 10px;
        }

        .login-form .forgot-password {
            display: block;
            font-size: 1.2rem;
            color: var(--bittersweet);
            text-align: right;
            margin-top: 10px;
        }

        .login-form .forgot-password:hover {
            text-decoration: underline;
        }

        .login-form .signup-text {
            font-size: 1.4rem;
            color: var(--onyx);
            margin-top: 20px;
        }

        .login-form .signup-link {
            color: var(--bittersweet);
            font-weight: var(--fw-600);
        }

        .login-form .signup-link:hover {
            text-decoration: underline;
        }

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
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login to Your Account</h2>

        <!-- Display errors -->
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form class="login-form" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    value="<?php echo htmlspecialchars($email); ?>"
                    required />
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    required />
            </div>

            <a href="forgot-password.php" class="forgot-password">
                Forgot Password?
            </a>

            <button type="submit" class="btn btn-primary">Login</button>

            <p class="signup-text">
                Don't have an account?
                <a href="register.php" class="signup-link">Sign Up</a>
            </p>
        </form>
    </div>

    <!-- Scripts -->
    <script
        type="module"
        src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script
        nomodule
        src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>