<?php
$error ="";
$msg = "";
session_start();
if (array_key_exists('logout', $_GET)) {
    unset($_SESSION['id']);
    session_destroy();
    setcookie('id', '', time() - 60*60);
    $_COOKIE['id'] = "";

}
else if ((array_key_exists('id', $_SESSION) AND $_SESSION['id']) || (array_key_exists('id', $_COOKIE) AND $_COOKIE['id'])){
    header('location: home.php');
}
if (array_key_exists('email', $_POST) AND array_key_exists('password', $_POST)) {
    $email = $_POST['email'];
    $password =$_POST['password'];
    
    if ($email == null || $email == "") {
        $msg = "Please enter a valid email address";
    }
    else if ($password == null || $password == ""){
        $msg = "Please enter a valid password";
    }
    else {
       include "connection.php";
        $hash = password_hash($password, PASSWORD_BCRYPT);
        if (isset($_POST['signup'])){
            $query = "SELECT 'id' FROM Users WHERE email = '".mysqli_real_escape_string($link, $email) ."'";
            if ($result = mysqli_query($link, $query)) {
                if (0 != mysqli_num_rows($result)) {
                    //already registered
                    $msg = "Email address is already registered. Try logging in.";
                } else {
                    $query = "INSERT INTO Users (`email` ,`password`) VALUES ('".mysqli_real_escape_string($link, $email) ."', 
                                    '".mysqli_real_escape_string($link, $hash)."')";
                    if (mysqli_query($link, $query))
                    {
                        if (array_key_exists('keep', $_POST) && $_POST['keep'] == 1) {
                            $_SESSION['id'] = mysqli_insert_id($link);
                            setcookie("id", mysqli_insert_id($link), time()+60*60*24);
                        }
                        header('location: home.php');
                    }
                }
            } else {
                $msg = "We could not sign you up. Please try again after sometime.";
            }
        }
        else if (isset($_POST['login'])) {
            $query = "SELECT * FROM Users WHERE email = '".mysqli_real_escape_string($link, $email) ."'";
            if ($result = mysqli_query($link, $query)) {
                if (0 == mysqli_num_rows($result)) {
                    //not registered
                    $msg = "Email is not registered. Please sign up.";
                } else {
                    while ($row = mysqli_fetch_array($result)) {
                        if ($row['email'] == $email) {
                            if ( password_verify( $password, $row['password'])) {
                                if (array_key_exists('keep', $_POST) && $_POST['keep'] == 1) {
                                     $_SESSION['id'] = $row['id'];
                                     setcookie("id", $row['id'], time()+60*60*24);
                                }
                                header('location: home.php');
                            }
                            else {
                                $msg = "Invalid Email/Password combination.";
                            }
                             break;
                        }
                    }
                }
            }            
        }
    }
}
if ($msg != "") {
    $error = "<div class='alert alert-danger' role='alert'>".$msg."</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
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
        <div class="container inner-container">
            <h1 class="cover-heading">Dear Diary</h1>
            <p class="lead">A secret diary to store your deepest thoughts, securely.</p>
            <div> <?php echo $error; ?></div>
                <div id="login">
                <form method="post" class="form-signin">
                    <div class="form-group row">
                    <input type="email" name="email" id="lemail" placeholder="Your Email" class="form-control col-sm-4"/>
                    </div>
                    <div class="form-group row">
                    <input type="password" name="password" id="lpassword" placeholder="Password" class="form-control col-sm-4" />
                    </div>
                    <div class="checkbox" >
                        <label><input type="checkbox" name="keep" value="1" checked /> Remember me</label>
                    </div>
                    <input type="submit" value="Login" name="login" class="btn btn-lg btn-primary"/> 
                </form>
                <div class="toggle">
                    <a>Not a member yet? Sign Up.</a>
                </div>
                </div>
            <div id="signup">
                <form method="post" class="form-signin">
                    <div class="form-group row">
                        <input type="email" name="email" id="semail" placeholder="Your Email" class="form-control col-sm-6" />
                    </div>
                    <div class="form-group row">
                    <input type="password" name="password" id="spassword" placeholder="Password" class="form-control col-sm-6"/>
                    </div>
                    <div class="checkbox" >
                        <label><input type="checkbox" name="keep" value="1" checked /> Remember me </label>
                    </div>
                    <input type="submit" value="Sign Up" name="signup" class="btn btn-lg btn-primary" />
                </form>
                <div class="toggle">
                    <a>Already a member? Log in.</a>
                </div>
            </div>
                
        </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js" integrity="sha384-Plbmg8JY28KFelvJVai01l8WyZzrYWG825m+cZ0eDDS1f7d/js6ikvy1+X+guPIB" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min.js" integrity="sha384-ux8v3A6CPtOTqOzMKiuo3d/DomGaaClxFYdCu2HPMBEkf6x2xiDyJ7gkXU0MWwaD" crossorigin="anonymous"></script>
    <script>
       $(".toggle").click(function() {
               $("#login").toggle();
                $("#signup").toggle();
       });
        </script>
    </body>
</html>