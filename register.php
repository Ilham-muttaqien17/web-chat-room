<?php 

require "db/Users.php";

if(isset($_POST['register'])) {

    $objUser = new Users;
    $regisMessage = $objUser->registerNewUser($_POST);
    var_dump($regisMessage);

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
</head>
<body>
<div class="w-4/12 max-h-max mx-auto shadow-lg shadow-gray-200 rounded-lg border p-10 mt-10">
        <img class="h-20 w-20 mx-auto" src="./img/icons/login.svg" alt="Register Icon">
        <h1 class="text-center font-semibold">Register User</h1>
        <form class="mt-8 flex flex-col gap-y-4" action="" method="POST">
            <div class="flex flex-col">
                <label class="text-sm" for="name">Name</label>
                <input class="h-10 pl-2 outline-none border border-gray-200 rounded-lg focus:border-blue-500" type="name" name="name" id="email">
            </div>
            <div class="flex flex-col">
                <label class="text-sm" for="email">Email</label>
                <input class="h-10 pl-2 outline-none border border-gray-200 rounded-lg focus:border-blue-500" type="email" name="email" id="email">
            </div>
            <div class="flex flex-col">
                <label class="text-sm" for="password">Password</label>
                <input class="h-10 pl-2 outline-none border border-gray-200 rounded-lg focus:border-blue-500" type="password" name="password" id="password">
            </div>
            <div class="flex flex-col">
                <label class="text-sm" for="cpassword">Confirm password</label>
                <input class="h-10 pl-2 outline-none border border-gray-200 rounded-lg focus:border-blue-500" type="password" name="cpassword" id="cpassword">
            </div>
            <button class="bg-blue-500 hover:bg-blue-600 w-full py-2 rounded-lg text-white font-semibold" type="submit" name="register">Register</button>
        </form>
        <div class="flex flex-col text-center mt-5">
            <p>Already have an account yet?</p>
            <a class="text-blue-500 font-semibold hover:text-blue-600" href="./login.php">Sign in</a>
        </div>
    </div>
</body>
</html>