<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <title>Login Page</title>

    <style>
        body{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #loginForm{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 95vh;
            width: 500px;
        }
    </style>
</head>

<body>

    <form id="loginForm" method="POST">

        <h1>Log In</h1>
        
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">
                <img src="https://static.thenounproject.com/png/4853889-200.png" height="20px"/>
            </span>
            <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" id="username" name="username" required>
            <br />
        </div>
        
        <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon1">
                <img src="https://www.svgrepo.com/show/381034/ui-basic-app-security-password-key.svg" height="20px"/>
            </span>
            <input type="password" id="password" name="password" required class="form-control" placeholder="Password" aria-label="Username" aria-describedby="basic-addon1" id="username" name="username" required>
            <br />
        </div>

        <div class="buttons">
            <button type="submit" name="login" value="Login" class="btn btn-outline-primary">Log In</button>
            <button type="button" class="btn btn-outline-secondary" onclick="location.href='register.php';">Register</button>
        </div>

        </form>

    <?php
    require "db.php";
    session_start();
    
    if (isset($_POST["login"])) {
    
    if (authenticate($_POST["username"], $_POST["password"]) == 1) {
        $_SESSION["username"]= $_POST["username"];
        $_SESSION["customer_id"] = $customer_id;
        header("LOCATION: customerMainPage.php");
        exit();
    }
    else {
        echo '<p style="color:red">Invalid username or password</p>'; 
    }
    }
    ?>

</body>

</html>