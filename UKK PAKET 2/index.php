<?php 
// session start
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}




// database connections
$konek = mysqli_connect("localhost", "root", "", "to_do_list");


// input data to database
if (isset($_POST['submit'])) {
    $task = $_POST['task'];
    $priority = $_POST['priority'];
    $end_date = $_POST['end_date'];

    if (!empty($task) && !empty($priority) && !empty($end_date)) {
        mysqli_query($konek, "INSERT INTO task (TASK, PRIORITY, DUE_DATE) VALUES ('$task', '$priority', '$end_date')"); 
        echo "<script>alert('Task Added Successfully')</script>"; 
    } else {
        echo "<script>alert('Please fill all the fields')</script>";}
}


// get pagination limit 
$limit = 5;


// pagination logic
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;


// get total number of rows
$total_rows_query = "SELECT COUNT(*) FROM task";
$total_rows_hasil = mysqli_query($konek, $total_rows_query);
$total_rows = mysqli_fetch_array($total_rows_hasil)[0];
$total_pages = ceil($total_rows / $limit);


// show data from database per page
$kueri = "SELECT * FROM task ORDER BY STATUS ASC, PRIORITY DESC, DUE_DATE ASC LIMIT $limit OFFSET $offset";  
$hasil = mysqli_query($konek, $kueri);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UKK RPL PAKET 2</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .table-zigzag tbody tr:nth-child(odd) {
            background-color: #f2f2f2; 
        }
        .table-zigzag tbody tr:nth-child(even) {
            background-color: #fff;
        }
        .priority-label {
            display: inline-block;
            width: 80px;
            text-align: center;
        }
        .done-label {
            display: inline-block;
            width: 70px;
            text-align: center;
        }

        /* confirm delete box modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            position: relative;
            transform: translateY(80%);
            border-radius: 20px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            right: 20px;
            top: 10px;
        }
        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
            gap: 10px;
        }
    </style>


    <!-- confirm delete script -->
    <script>
        function confirmDelete(id) {
            document.getElementById('deleteModal').style.display = 'block';
            document.getElementById('confirmDelete').onclick = function() {
                window.location.href = 'delete.php?id=' + id;
            }
        }

        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        function toggleInputSection() {
            var inputSection = document.getElementById('inputSection');
            var tableSection = document.getElementById('tableSection');
            var toggleLink = document.getElementById('toggleLink');
            if (inputSection.classList.contains('hidden')) {
                inputSection.classList.remove('hidden');
                tableSection.classList.add('hidden');
                toggleLink.textContent = 'Close';
            } else {
                inputSection.classList.add('hidden');
                tableSection.classList.remove('hidden');
                toggleLink.textContent = 'Add Task';
            }
        }
        
        function hideInputSection() {
            var inputSection = document.getElementById('inputSection');
            var tableSection = document.getElementById('tableSection');
            var toggleLink = document.getElementById('toggleLink');
            inputSection.classList.add('hidden');
            tableSection.classList.remove('hidden');
            toggleLink.textContent = 'Add Task';
        }
    </script>
</head>
<body class="bg-slate-900">
    <!-- delete confirmations modal -->
     <div class="modal" id="deleteModal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p class="font-semibold text-lg pb-2">Are you sure you want to delete this task?</p>
            <div class="modal-footer">
                <button id="confirmDelete" class="bg-red-500 text-white px-4 py-2 rounded-md">Delete</button>         
                <button onclick="closeModal()" class="bg-slate-600 text-white px-4 py-2 rounded-md">Cancel</button>
            </div>
        </div>
     </div>


<!-- header/navbar -->
    <header class="bg-blue-500 text-white p-4 bg-opacity-50">
        <div class="container mx-auto flex items-center justify-between ">
            <div class="flex items-center">
                <h1 class="text-2xl font-bold">UKK RPL PAKET 2</h1>
            </div>
            <nav class="mt-2">
                <ul class="flex space-x-4">   
                    <li class="block lg:hidden"><a href="javascript:void(0);" id="toggleLink" onclick="toggleInputSection()" class="hover:underline">Add Task</a>
                    </li>      
                    <li><a href="logout.php" class="hover:underline">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>



<!-- body, place where show input sections -->
 <div class="container mx-auto mt-8 flex flex-col lg:flex-row">
    <div id="inputSection" class="bg-white p-6 rounded-lg shadow-xl shadow-grey-500/50 border border-gray-300 lg:w-1/3 mr-5 lg:mb-0 lg:mr-5 hidden lg:block">
    <h2 class="text-3xl font-bold mb-5 text-center">TASK INPUT</h2>
    <br>
    <form action="" method="post">
        <!-- task -->
        <div class="mb-5">
            <label for="task" class="block text-blue-500 text-bold">TASK</label>
            <input type="text" name="task" id="task" class="mt-1 block w-full border border-gray-300 rounded-md p-2 bg-slate-300" required placeholder="Input Task Here">
        </div>
        <br>
        <!-- priority -->
        <div class="mb-5">
            <label for="priority" class="block text-blue-500 text-bold">PRIORITY</label>
            <select name="priority" id="priority" class="mt-1 block w-full border border-gray-300 rounded-md p-2 bg-slate-300" required>
                <option selected disabled hidden>--Choose Priority--</option>
                <option value="1">LOW PRIORITY</option>
                <option value="2">MEDIUM PRIORITY</option>
                <option value="3">HIGH PRIORITY</option>
            </select>
        </div>
        <br>
        <!-- deadline -->
        <div class="mb-5">
            <label for="end_date" class="block text-blue-500 text-bold">DEADLINE</label>
            <input type="date" name="end_date" id="end_date" class="mt-1 block w-full border border-gray-300 rounded-md p-2 bg-slate-300" value="<?= date('Y-m-d') ?>" required>
        </div>
        <br>
        <!-- submit button -->
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md" name="submit">SUBMIT!!</button>
        </div>
    </form>
    </div>   




<!-- table sections, show task table that already inputed -->
    
        <div id="tableSection" class="bg-white p-6 rounded-lg shadow-xl shadow-grey-500/50 border border-gray-300 lg:w-2/3">
            <h2 class="text-3xl font-bold mb-5 text-center">TASKS LIST</h2>
            <table class="min-w-full bg-white table-zigzag">
                <thead>
                    <tr>
                        <th class="text-center py-3 px-4 uppercase font-semibold text-sm bg-slate-500 text-white">No.</th>
                        <th class="text-center py-3 px-4 uppercase font-semibold text-sm bg-slate-500 text-white">Task</th>
                        <th class="text-center py-3 px-4 uppercase font-semibold text-sm bg-slate-500 text-white">Priority</th>
                        <th class="text-center py-3 px-4 uppercase font-semibold text-sm bg-slate-500 text-white">Deadline</th>
                        <th class="text-center py-3 px-4 uppercase font-semibold text-sm bg-slate-500 text-white">Status</th>
                        <th class="text-center py-3 px-4 uppercase font-semibold text-sm bg-slate-500 text-white">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = $offset + 1 ;
                    while ($data = mysqli_fetch_assoc($hasil)) :  ?>

                        
                        <tr>
                            <td class="text-center py-3 px-4"><?= $no++ ?></td>
                            <td class="text-left py-3 px-4"><?= $data['TASK'] ?></td>
                            <td class="text-center py-3 px-4">
                                <?php if ($data['PRIORITY'] == 1) { ?>
                                    <span class="priority-label inline-block px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded-full">LOW</span>
                                <?php } elseif ($data['PRIORITY'] == 2) { ?>
                                    <span class="priority-label inline-block px-2 py-1 text-xs font-semibold text-white bg-yellow-500 rounded-full">MEDIUM</span>
                                <?php } else { ?>
                                    <span class="priority-label inline-block px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">HIGH</span>
                                <?php } ?>
                            </td>
                            <td class="text-center py-3 px-4"><?= $data['DUE_DATE'] ?></td>
                            <td class="text-center py-3 px-4">
                                <?php if ($data['STATUS'] == 1) { ?>
                                    <span class="done-label inline-block px-2 py-1 text-xs font-semibold text-white bg-emerald-500 rounded-md">DONE</span>
                                <?php } else { ?>
                                    <span class="done-label inline-block px-2 py-1 text-xs font-semibold text-white bg-amber-400 rounded-md">UNDONE</span>
                                <?php } ?>
                            </td>
                            <td class="text-center py-3 px-4">
                                <?php if ($data['STATUS'] != 1) { ?>

                                    <a href="done.php?id=<?php echo $data['ID']?>" class="inline-block px-3 py-1 text-xs font-semibold text-white bg-green-500 rounded-md"><i class='bx bx-check'></i> Done</a>
                                
                                    <a href="edit.php?id=<?php echo $data['ID']?>" class="inline-block px-3 py-1 text-xs font-semibold text-white bg-blue-500 rounded-md"><i class='bx bxs-cog'></i> Edit</a>   
                                
                                <?php } ?>

                                <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $data['ID']?>)" class="inline-block px-3 py-1 text-xs font-semibold text-white bg-red-700 rounded-md"><i class='bx bxs-trash' ></i> Delete</a>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>

            <!-- pagination -->
             <div class="flex justify-center mt-4">
                <!-- previous page -->
                <?php if ($page > 1) :?>
                    <a href="?page=<?= $page - 1?>" class="px-3 py-1 bg-blue-500 text-white rounded-md mx-1">Previous</a>
                <?php endif ?> 

                <!-- current page -->
                <?php for ($i=1; $i <= $total_pages ; $i++) : ?>
                    <a href="?page=<?= $i?>" class="px-3 py-1 <?= $i == $page ? 'bg-blue-700' : 'bg-blue-500'?> text-white rounded-md mx-1"><?= $i ?></a>
                <?php endfor ?>

                <!-- next page --> 
                <?php if ($page < $total_pages) :?>
                    <a href="?page=<?= $page + 1 ?>" class="px-3 py-1 bg-blue-500 text-white rounded-md mx-1">Next</a>
                <?php endif ?>

             </div>
        </div>
    </div>

</body>
</html>