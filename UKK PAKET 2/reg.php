<?php 
$konek = mysqli_connect("localhost", "root", "", "to_do_list");

function register($data) {
    global $konek;

    $username = strtolower(stripslashes($data['username']));
    $password = mysqli_real_escape_string($konek, $data['password']);
    $password2 = mysqli_real_escape_string($konek, $data['password2']);

    $result = mysqli_query($konek, "SELECT username FROM users WHERE username = '$username'");

    if (mysqli_fetch_assoc($result)) {
        echo "<script>
                alert('Username already exists');
            </script>";
        return false;
    }

    if ($password !== $password2) {
        echo "<script>
                alert('Password does not match');
            </script>";
        return false;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    mysqli_query($konek, "INSERT INTO users VALUES('', '$username', '$password')");

    return mysqli_affected_rows($konek);
}



if (isset($_POST['register'])) {
    
    if ( register($_POST) > 0) {
        echo "<script>
        alert('Account has been created');
        </script>";
    } else {
        echo mysqli_error($konek);
    }
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
        <h1 class="text-2xl font-bold text-gray-800">Register</h1>
        <form action="" method="post" class="mt-4">

        <div class="boxicon">
            <i class='bx bxs-user'></i>
            <label for="username" class="block text-sm text-gray-800 inline-block">Username</label>
            <input type="text" name="username" id="username" class="w-full p-2 border border-gray-300 rounded-lg mt-1" required placeholder="Username"></i>

            <i class='bx bxs-lock'></i>
            <label for="password" class="block text-sm text-gray-800 mt-4 inline-block">Password</label>
            <input type="password" name="password" id="password" class="w-full p-2 border border-gray-300 rounded-lg mt-1" required placeholder="Password"></i>

            <i class='bx bxs-lock'></i>
            <label for="password" class="block text-sm text-gray-800 mt-4 inline-block">Confirm Password</label>
            <input type="password" name="password2" id="password2" class="w-full p-2 border border-gray-300 rounded-lg mt-1" required placeholder="Confirm Password"></i>
        </div>

            <button type="submit" class="w-full bg-blue-500 text-white rounded-lg p-2 mt-4" name="register">Register</button>

            <a href="login.php" class="text-gray-800 py-2 text-sm hover:underline hover:text-blue-500">Already have an Account? Login</a>
        </form>
    
</body>
</html>