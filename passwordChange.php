<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <title>Password Change</title>

    <style>
        body{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #passwordChangeForm{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 500px;
        }
    </style>
</head>
<body>

    <form id="passwordChangeForm" method="POST">

    <div class="mb-3">
  <label for="formGroupExampleInput" class="form-label">Old Password:</label>
  <input type="password" id="oldPassword" name="oldPassword" required class="form-control" id="formGroupExampleInput">
</div>
<div class="mb-3">
  <label for="formGroupExampleInput2" class="form-label">New Password:</label>
  <input type="password" id="newPassword" name="newPassword" required class="form-control" id="formGroupExampleInput2" >
</div>
<div class="mb-3">
  <label for="formGroupExampleInput2" class="form-label">Confirm Password:</label>
  <input type="password" id="confirmNewPassword" name="confirmNewPassword" required class="form-control" id="formGroupExampleInput2" >
</div>
    <div class="buttons">
    <button type="submit" class="btn btn-outline-danger">Update Password</button>
    <button type="button" class="btn btn-outline-secondary" onclick="location.href='customerMainPage.php';" class="btn btn-outline-secondary">Back</button>
    </div>
    </form>

    <?php

    require "db.php";
    session_start();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = $_SESSION["username"];
        $oldPassword = $_POST["oldPassword"];
        $newPassword = $_POST["newPassword"];
        $confirmNewPassword = $_POST["confirmNewPassword"];

        if ($newPassword === $confirmNewPassword) {
            $passwordChangeResult = changePassword($username, $oldPassword, $newPassword);

            if ($passwordChangeResult) {
                echo "<div class='alert alert-success' role='alert'>Password change successful!</div>";
            } else {
                echo "<div class='alert alert-danger' role='alert'>Incorrect old password. Password change failed.</div>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>New passwords do not match.</div>";
        }
    }

    ?>
    
</body>
</html>