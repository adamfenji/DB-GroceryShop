<?php
session_start();

if (isset($_SESSION["username"])) {
    if (isset($_POST['confirm_logout'])) {
        // User confirmed logout
        session_unset();
        session_destroy();
        header("Location: browsingMainPage.php");
        exit();
    }
} else {
    // If user is not logged in, redirect to the main page
    header("Location: browsingMainPage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <title>Logout</title>

    <style>
        body{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .logoutContainer{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 90vh;
            width: 500px;
        }
    </style>
</head>

<body>

    <div class="logoutContainer">

        <h2>Confirm Logout</h2>
        <p>Are you sure you want to logout?</p>

        <form method="post">
            <button type="submit" name="confirm_logout" class="btn btn-outline-danger">Log Out</button>
            <button type="button" onclick="location.href='customerMainPage.php';" class="btn btn-outline-secondary">Back</button>
        </form>

    </div>

</body>

</html>