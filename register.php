<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <title>Registration Page</title>

    <style>
        body{
          display: flex;
          justify-content: center;
          align-items: center;
          margin-top: 100px;
          /* background-color: #cacbc8; */
        }

        label{
          font-weight: bold;
        }

        #registrationForm{
          height: 80vh;
          width: 500px;
        }
    </style>
</head>

<body>

<form class="row g-3" id="registrationForm" method="POST">
  <div class="col-md-6">
    <label for="inputEmail4" class="form-label">Email:</label>
    <input type="email" id="email" name="email" required class="form-control" aria-label="Username" aria-describedby="basic-addon1" placeholder="Email">
  </div>
  <div class="col-md-6">
    <label for="inputEmail4" class="form-label">Username:</label>
    <input type="text" id="username" name="username" required class="form-control" aria-label="Username" aria-describedby="basic-addon1" placeholder="Username">
  </div>
  <div class="col-md-6">
    <label for="inputEmail4" class="form-label">Password:</label>
    <input type="password" id="password" name="password" required class="form-control" aria-label="Username" aria-describedby="basic-addon1" placeholder="Password">
  </div>
  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">Confirm Password:</label>
    <input type="password" id="confirmPassword" name="confirmPassword" required class="form-control" aria-label="Username" aria-describedby="basic-addon1" placeholder="Confirm Password">
  </div>
  <div class="col-md-6">
    <label for="inputEmail4" class="form-label">First Name:</label>
    <input type="text" id="firstName" name="firstName" required aria-label="First name" class="form-control" placeholder="First Name">
  </div>
  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">Last Name:</label>
    <input type="text" id="lastName" name="lastName" required aria-label="Last name" class="form-control" placeholder="Last Name">
  </div>
  <div class="col-12">
    <label for="inputAddress" class="form-label">Shipping Address:</label>
    <textarea id="shippingAddress" name="shippingAddress" rows="4" required class="form-control" aria-label="With textarea"></textarea>
  </div>
  <div class="col-12">
  <button type="submit" class="btn btn-outline-primary">Register</button>
        <button type="button" class="btn btn-outline-secondary" onclick="location.href='login.php';">Log In</button>
  </div>
</form>

    <?php
    require "db.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirmPassword"];
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $email = $_POST["email"];
        $shippingAddress = $_POST["shippingAddress"];

        $registrationResult = registerUser($username, $password, $firstName, $lastName, $email, $shippingAddress);

        if ($registrationResult === 'success') {
            echo '<p style="color:green">Registration successful!</p>';
            header("LOCATION: customerMainPage.php");
            exit();
        } else {
            echo '<p style="color:red">Registration failed. ';
            if ($registrationResult === 'userExist') {
                echo 'Username or email already taken. ';
            }
            if ($registrationResult === 'passwordMismatch') {
                echo 'Passwords do not match. ';
            }
            echo '</p>';
        }
    }
    ?>

</body>

</html>