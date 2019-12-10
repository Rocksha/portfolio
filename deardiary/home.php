<?php
session_start();

if (array_key_exists('id', $_COOKIE)) {
    $_SESSION['id'] = $_COOKIE['id'];
}
//If the session doesn't exist, redirect to index.php page to login.
if (!array_key_exists('id', $_SESSION))   {
    header('location: index.php');
}

$value = "";
include "connection.php";
$query = "SELECT `diary` FROM Users WHERE `id` = '".$_SESSION['id']."' LIMIT 1";
if ($result = mysqli_query($link, $query))
{
    $value = mysqli_fetch_array($result)['diary'];
}



?>


<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Dear Diary</title>
        <link rel="shortcut icon" href="images/featherlogo.png" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css" integrity="sha384-MIwDKRSSImVFAZCVLtU0LMDdON6KVCrZHyVQQj6e8wIEJkW4tvwqXrbMIya1vriY" crossorigin="anonymous">
        <link href="styles.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <p class="title">Dear Diary</p>
                <a href="index.php?logout">
                    <input type="submit" name="logout" value="Log Out" class="btn btn-lg btn-primary" id="logout" />
                </a>
                <form method="post" class="form-group" id="diaryform">
                    <textarea name="diary" id="diary" class="form-control" rows="35"><?php echo ($value); ?></textarea>
                </form>
            </div>
        
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js" integrity="sha384-Plbmg8JY28KFelvJVai01l8WyZzrYWG825m+cZ0eDDS1f7d/js6ikvy1+X+guPIB" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min.js" integrity="sha384-ux8v3A6CPtOTqOzMKiuo3d/DomGaaClxFYdCu2HPMBEkf6x2xiDyJ7gkXU0MWwaD" crossorigin="anonymous"></script>
        <script>
            $('#diary').bind('input propertychange', function() {
                $.ajax({
                      method: "POST",
                      url: "update.php",
                      data: { content: $("#diary").val() }
                    })
            });
            
        
        </script>
    </body>
</html>