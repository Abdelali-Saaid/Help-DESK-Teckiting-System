<?php 
    session_start();
    if (isset($_SESSION['user'])) header('location: dashboard.php');


    $error_message = '';

    if($_POST){
        include('database/connection.php');

        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = 'SELECT * FROM users WHERE users.email="'. $username .'" AND users.password="'. $password .'"';
        $stmt = $conn->prepare($query);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $user = $stmt->fetchAll()[0];
            $_SESSION['user'] = $user;

            header('Location: dashboard.php');
        } else $error_message = 'Please make sure that username and password are correct.';

        $stmt = $conn->prepare("SELECT * FROM users");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $users = $stmt->fetchAll();

        $user_exist = false;
        foreach($users as $user){
            $upass = $user['password'];

            if(password_verify($password, $upass)){
                $user_exist = true;
                $user['permissions'] = explode(',', $user['permissions']);
                $_SESSION['user'] = $user;
                break;
            }
        }

        if($user_exist) header('Location: dashboard.php');
        else $error_message = 'Please make sure that username and password are correct.';
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title >HELP DESK</title>
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body id="loginBody">
        <?php if(!empty($error_message)) { ?>
            <div id="errorMessage">
                <strong>ERROR:</strong><p class=""></p><?= $error_message ?></p>
            </div>
        <?php } ?>    
        <div class="container">
            <div class="loginHeader">
                <h1> HELP DESK</h1>
                <p> Manage Tasks</p>
            </div>
            <div class="loginBody">
                <form action="login.php" method="POST"> 
                    <div class="loginInputsContainer">
                        <label for="name"> Username</label>
                        <input placeholder="username" name="username" type="text">
                    </div>
                    <div class="loginInputsContainer">
                        <label for="password"> Password</label>
                        <input placeholder="password" name="password" type="password">
                    </div>
                    <div class="loginButtonContainer">
                        <button> login</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>