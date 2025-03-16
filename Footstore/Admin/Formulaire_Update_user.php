<?php
session_start();

// Ensure the user is an admin
if (!isset($_SESSION['user']) && $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once '../pdo.php';

if (!isset($_GET['id'])) {
    header('Location: User.php');
    exit;
}

$user_id = $_GET['id'];

// Fetch the user's current data
try {
    $sql = "SELECT * FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $_SESSION['error'] = "User not found.";
        header('Location: User.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Error fetching user data: " . $e->getMessage();
    header('Location: User.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $role = $_POST['role']; // Assuming you have a role field in your form

    // Validate input (you can add more validation as needed)
    if (empty($username) || empty($email) || empty($first_name) || empty($last_name)) {
        $_SESSION['error'] = "All fields are required.";
    } else {
        try {
            // Update the user in the database
            $sql = "UPDATE users SET username = :username, email = :email, first_name = :first_name, last_name = :last_name, role = :role WHERE user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':first_name' => $first_name,
                ':last_name' => $last_name,
                ':role' => $role,
                ':user_id' => $user_id
            ]);

            $_SESSION['message'] = "User updated successfully.";
            header('Location: USER.php');
            exit;
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error updating user: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom Animation for Inputs */
        @keyframes focusInput {
            0% {
                transform: scale(1);
                opacity: 0.6;
            }
            100% {
                transform: scale(1.05);
                opacity: 1;
            }
        }

        /* Hover effect for submit button */
        .btn-submit:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }

        /* Background Gradient */
        .bg-gradient {
            background: linear-gradient(135deg, rgba(249, 115, 22, 0.7), rgba(234, 88, 12, 0.7)), url('https://www.example.com/background-image.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        /* Focus Animation */
        .focus\:animate-input:focus {
            animation: focusInput 0.3s ease-out;
        }

        /* Parallax Effect (Optional) */
        .parallax {
            position: relative;
            z-index: -1;
            animation: moveBackground 30s linear infinite;
        }

        @keyframes moveBackground {
            0% {
                background-position: 0% 0%;
            }
            100% {
                background-position: 100% 100%;
            }
        }

        /* Left Rectangle Style */
        .left-rectangle {
            background: #ffffff;
            box-shadow: 4px 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
            width: 40%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .left-rectangle::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 150%;
            height: 150%;
            background: rgba(234, 88, 12, 0.3);
            border-radius: 50%;
            animation: pulse 2s infinite ease-out;
        }

        /* Pulsing animation for left rectangle */
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.6;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>
<body class="bg-gray-100 py-8 flex items-center justify-center min-h-screen bg-gradient">

    <div class="w-full max-w-6xl mx-auto flex items-center justify-center gap-8 relative z-10">
        <!-- Left Rectangle -->
        <div class="left-rectangle">
            <div class="text-center">
                <h2 class="text-3xl font-semibold text-gray-800 mb-6">Welcome !</h2>
                <p class="text-gray-500">Update User account here !</p>
            </div>
        </div>

        <!-- Form Container -->
        <div class="w-full max-w-md mx-auto bg-white p-8 rounded-lg shadow-lg transform transition-transform duration-300 hover:scale-105 relative z-10">
            <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Update User</h2>

            <!-- Display error or success messages -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?= $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?= $_SESSION['message']; ?>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <form action="Formulaire_Update_user.php?id=<?= $user_id ?>" method="POST">
                <!-- Username Input -->
                <div class="relative z-0 w-full mb-5 group">
                    <input type="text" name="username" id="floating_username" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-orange-500 peer focus:animate-input" placeholder=" " value="<?= htmlspecialchars($user['username']) ?>" required />
                    <label for="floating_username" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:text-orange-500 peer-focus:dark:text-orange-400">Username</label>
                </div>

                <!-- Email Input -->
                <div class="relative z-0 w-full mb-5 group">
                    <input type="email" name="email" id="floating_email" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-orange-500 peer focus:animate-input" placeholder=" " value="<?= htmlspecialchars($user['email']) ?>" required />
                    <label for="floating_email" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:text-orange-500 peer-focus:dark:text-orange-400">Email Address</label>
                </div>

                <!-- First Name Input -->
                <div class="relative z-0 w-full mb-5 group">
                    <input type="text" name="first_name" id="floating_first_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-orange-500 peer focus:animate-input" placeholder=" " value="<?= htmlspecialchars($user['first_name']) ?>" required />
                    <label for="floating_first_name" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:text-orange-500 peer-focus:dark:text-orange-400">First Name</label>
                </div>

                <!-- Last Name Input -->
                <div class="relative z-0 w-full mb-5 group">
                    <input type="text" name="last_name" id="floating_last_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-orange-500 peer focus:animate-input" placeholder=" " value="<?= htmlspecialchars($user['last_name']) ?>" required />
                    <label for="floating_last_name" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:text-orange-500 peer-focus:dark:text-orange-400">Last Name</label>
                </div>

                <!-- Role Input -->
                <div class="relative z-0 w-full mb-5 group">
                    <select name="role" id="floating_role" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-orange-500 peer focus:animate-input" required>
                        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                    <label for="floating_role" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:text-orange-500 peer-focus:dark:text-orange-400">Role</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit text-white bg-orange-600 hover:bg-orange-700 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-orange-500 dark:hover:bg-orange-600 dark:focus:ring-orange-700 transition-transform duration-300">Update</button>
            </form>
        </div>
    </div>

    <!-- Parallax effect background (if needed) -->
    <div class="parallax absolute top-0 left-0 w-full h-full bg-cover bg-center" style="background-image: url('https://www.example.com/background-image.jpg');"></div>

</body>
</html>