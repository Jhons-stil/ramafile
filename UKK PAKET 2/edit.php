<?php 
$konek = mysqli_connect("localhost", "root", "", "to_do_list");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $kueri = "SELECT * FROM task WHERE ID = $id";
    $hasil = mysqli_query($konek, $kueri);
    $data = mysqli_fetch_assoc($hasil);
}

// input data to database
if (isset($_POST['submit'])) {
    $task = $_POST['task'];
    $priority = $_POST['priority'];
    $end_date = $_POST['end_date'];

    if (!empty($task) && !empty($priority) && !empty($end_date)) {
        mysqli_query($konek, "UPDATE task (TASK, PRIORITY, DUE_DATE) VALUES ('$task', '$priority', '$end_date')"); 
        echo "<script>alert('Task Has Been Updated'); window.location.href='index.php';</script>"; 
    } else {
        echo "<script>alert('Please fill all the fields')</script>";}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UKK RPL PAKET 2</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        .table-zigzag tbody tr:nth-child(odd) {
            background-color: #f2f2f2; 
        }
        .table-zigzag tbody tr:nth-child(even) {
            background-color: #fff;
        }
    </style>
</head>
<body>


<!-- header/navbar -->
    <header class="bg-blue-500 text-white p-4 bg-opacity-50">
        <div class="container mx-auto flex items-center justify-between ">
            <div class="flex items-center">
                <h1 class="text-2xl font-bold">UKK RPL PAKET 2</h1>
            </div>
            <nav class="mt-2">
                <ul class="flex space-x-4">
                    <li><a href="#" class="hover:underline">Home</a></li>    
                    <li><a href="#" class="hover:underline">About</a></li>    
                    <li><a href="#" class="hover:underline">Contact</a></li>    
                </ul>
            </nav>
        </div>
    </header>



<!-- body, place where show input sections -->
 <div class="container mx-auto mt-8">
    <div class="bg-white p-6 rounded-lg shadow-xl shadow-grey-500/50 border border-gray-300">
    <h2 class="text-3xl font-bold mb-5 text-center">EDIT TAST</h2>
    <br>
    <form action="" method="post">
        <!-- task -->
        <div class="mb-5">
            <label for="task" class="block text-blue-500 text-bold">TASK</label>
            <input type="text" name="task" id="task" class="mt-1 block w-full border border-gray-300 rounded-md p-2 bg-slate-300" required value="<?= $data['TASK'] ?>">
        </div>
        <br>
        <!-- priority -->
        <div class="mb-5">
            <label for="priority" class="block text-blue-500 text-bold">PRIORITY</label>
            <select name="priority" id="priority" class="mt-1 block w-full border border-gray-300 rounded-md p-2 bg-slate-300" required>
                <option selected disabled>--Choose Priority--</option>
                <option value="1" <?= $data['PRIORITY'] == 1 ? 'selected' : ''?>>LOW PRIORITY</option>
                <option value="2" <?= $data['PRIORITY'] == 2 ? 'selected' : ''?>>MEDIUM PRIORITY</option>
                <option value="3" <?= $data['PRIORITY'] == 3 ? 'selected' : ''?>>HIGH PRIORITY</option>
            </select>
        </div>
        <br>
        <!-- deadline -->
        <div class="mb-5">
            <label for="end_date" class="block text-blue-500 text-bold">DEADLINE</label>
            <input type="date" name="end_date" id="end_date" class="mt-1 block w-full border border-gray-300 rounded-md p-2 bg-slate-300" value="<?= $data['DUE_DATE'] ?>" required>
        </div>
        <br>
        <!-- submit button -->
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md" name="submit">SUBMIT!!</button>
        </div>
    </form>
    </div>   
 </div>

</body>
</html>