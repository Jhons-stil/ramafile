<?php 
session_start();
$konek = mysqli_connect("localhost", "root", "", "to_do_list");

// check cookie
if (isset($_COOKIE['phase']) && isset($_COOKIE['key'])) {
    if ($_COOKIE['phase'] == 'true') {
        $_SESSION['key'] = true;
    }


    $result = mysqli_query($konek, "SELECT user_id FROM users WHERE user_id = '$_COOKIE[phase]'");
    $row = mysqli_fetch_assoc($result);

    if ($_COOKIE['key'] === hash('sha256', $row['user_id'])) {
        $_SESSION['login'] = true;
    }
}

// check if user has logged in
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}



if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = mysqli_query($konek, "SELECT * FROM users WHERE username = '$username'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            // set session
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $row['user_id'];

            // check remember me
            if (isset($_POST['remember'])) {
                setcookie('phase', $row['user_id'], time() + 60);
                setcookie('key', hash('sha256', $row['user_id']), time() + 60);
            }

            header("Location: index.php");
            exit;
        }
    }

    $error = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UKK RPL PAKET 2</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h3 class="text-3xl font-bold text-blue-500 text-center py-4">Tasks List</h3>
        <h1 class="text-2xl font-bold text-gray-800">Login</h1>
        <form action="" method="post" class="mt-4">

        <div class="boxicon">
            <i class='bx bxs-user'></i>
            <label for="username" class="block text-sm text-gray-800 inline-block">Username</label>
            <input type="text" name="username" id="username" class="w-full p-2 border border-gray-300 rounded-lg mt-1" required placeholder="Username"></i>

            <i class='bx bxs-lock'></i>
            <label for="password" class="block text-sm text-gray-800 mt-4 inline-block">Password</label>
            <input type="password" name="password" id="password" class="w-full p-2 border border-gray-300 rounded-lg mt-1" required placeholder="Password"></i>
        </div>
        <div class="mt-4">
            <input type="checkbox" name="remember" id="remember" class="mr-2">
            <label for="remember" class="text-sm text-gray-800">Remember me</label>
        </div>

            <button type="submit" class="w-full bg-blue-500 text-white rounded-lg p-2 mt-4" name="login">Login</button>

            <a href="reg.php" class="text-gray-800 py-2 text-sm hover:underline hover:text-blue-500">Doesn't have an Account? Register</a>
        </form>
    
</body>
</html>