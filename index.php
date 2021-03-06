<?php

require("db/Users.php");
require("db/Chats.php");
session_start();

if(!isset($_SESSION['user'])){
    header("location: login.php");
}

$obj = new Users;
$users = $obj->getAllUser();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Room Dashboard</title>

    <!-- Tailwindcss -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            setInterval(function() {
                $("#list_users").load(window.location.href + " #list_users");
            }, 1000)
        })
    </script>

</head>
<body>
    <div class="w-full sm:w-10/12 md:w-10/12 mx-auto flex flex-col items-center py-10 gap-y-4">
        <h1 class="text-center text-4xl font-semibold">Chat Room</h1>
        <p class="text-center font-lg mt-10">Daftar User</p>
        <?php 
            $obj->setEmail($_SESSION['user']);
            $user = $obj->getUserByEmail();
            echo "<input type='hidden' name='userId' id='userId' value='" . $user['user_id'] . "'>"; 
        ?>


        <!-- Table other users -->
        <table id="list_users" class="border mx-auto">
            <thead>
                <th>Name</th>
                <!-- <th>Email</th> -->
                <th>Last Login</th>
                <th>Status</th>
            </thead>
            
            <tbody>
                <?php 
                // Load other users from database
                    foreach($users as $key => $users) { 
                        $loginStatus = '';
                        if($users['login_status'] == 1) {
                            $loginStatus = "Online";
                        } else {
                            $loginStatus = "Offline";
                        }
                        if($users['email'] !== $_SESSION['user']) {
                ?>
                            <tr>
                                <?php
                                    echo "<td>" . $users['name'] . "</td>";
                                    // echo "<td>" . $users['email'] . "</td>"; 
                                    echo "<td>" . $users['last_login'] . "</td>";
                                    echo "<td>" . $loginStatus . "</td>";
                                ?>
                            </tr>
                    
                <?php 
                        }
                    } 
                ?>
            </tbody>
        </table>

        <div class="flex items-center mx-auto space-x-4">
            <a class="bg-red-500 px-4 py-1 text-white rounded-lg" href="logout.php">Logout</a>
            <a class="bg-green-500 px-4 py-1 text-white rounded-lg" href="private_chat.php" >Private Chat</a>
        </div>
        
        <!-- Chat box container -->
        <div class="w-full">
            <div class="bg-gray-200 w-8/12 h-[400px] mx-auto mt-10 p-4 flex flex-col gap-y-2 overflow-y-scroll" id="chat-container">
                <div class="bg-yellow-200 rounded-lg px-4 py-2 max-w-fit mx-auto">
                    <p class="text-center">Mohon gunakan bahasa yang sopan!</p>
                </div>
                <?php 

                    $chats = new Chats;
                    $chat = $chats->getAllChat();

                    // Load all messages from database
                    foreach($chat as $key => $chat) {

                        // Create new object for class users
                        $objUser = new Users;
                        $objUser->setEmail($_SESSION['user']);
                        $userData = $objUser->getUserByEmail();
                        $userId = $userData['user_id'];

                        $styleBox = '';

                        if($chat['user_id'] == $userId) {
                            $styleBox = 'bg-red-200 text-right ml-auto';
                            $chat['name'] = "Me";
                        } else {
                            $styleBox = 'bg-green-200 text-left';
                        }

                        echo '<div class="max-w-fit ' . $styleBox . ' rounded-xl px-4 py-2 ..."><small class="font-semibold">' . $chat['name'] . 
                        '</small><p class="">' . $chat['message'] . 
                        '</p><p class="text-right text-xs text-gray-400 ">' . $chat['created_at'] . 
                        '</p></div>';
                    }
                
                ?>
            </div>
            <form class="w-8/12 mx-auto flex" action="" method="POST">
                <input class="px-2 py-1 flex-1 border border-gray-400 outline-none" type="text" name="message" id="message" placeholder="Enter messages...">
                <button class="w-[80px] bg-blue-500 text-white" type="submit" name="send" id="send">Kirim</button>
            </form>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            // Initialize new websocket connection
            var conn = new WebSocket('ws://localhost:8080');
            conn.onopen = function(e) {
                console.log("Connection established!");
            };

            // Receive sent message
            conn.onmessage = function(e) {
                console.log(e.data);
                data = JSON.parse(e.data);

                var styleBox = '';

                if(data.from == "Me") {
                    styleBox = 'bg-red-200 text-right ml-auto';
                } else {
                    styleBox = 'bg-green-200 text-left';
                }
                
                var box = '<div class="' + styleBox + ' max-w-fit rounded-xl px-4 py-2 ..."><small class="font-semibold">' + data.from + 
                '</small><p class="">' + data.msg + 
                '</p><p class="text-right text-xs text-gray-400 ">' + data.dt + 
                '</p></div>';

                $("#chat-container").append(box);

            };

            // Trigger button for send message
            $("#send").click(function(event) {
                event.preventDefault();
                var msg = $("#message").val();
                var uid = $("#userId").val();

                var data = {
                    user_id: uid,
                    msg: msg
                };

                // Sent message
                conn.send(JSON.stringify(data));

                $("#message").val("");
            })
        })
    </script>
</body>
</html>