<?php
include('../includes/connect.php');
include('../functions/common_function.php');

if(isset($_POST['special_registration'])){
    $userid = $_POST['userid'];
    $password = $_POST['password'];
    $hash_password = password_hash($password, PASSWORD_DEFAULT);
    $confirm_password = $_POST['confirm_password'];
    $user_ip = getIPAddress();
    $special_character = $_POST['special_character'];

    // select query to check user exist
    $select_query = "SELECT * FROM special_login WHERE sp_id='$userid' or sp_password='$password' or sp_character='$special_character'";
    $result = mysqli_query($con, $select_query);
    $rows_count = mysqli_num_rows($result);

    if ($rows_count > 0) {
        echo "<script>alert('Username and Email already exist')</script>";
    } elseif ($password != $confirm_password) {
        echo "<script>alert('Password does not match')</script>";
    } else {
        // Continue with registration logic
        $insert_query = "INSERT INTO special_login (sp_id, sp_password, sp_character)
        VALUES ('$userid', '$hash_password', '$special_character')";
        $sql_execute = mysqli_query($con, $insert_query);

        if ($sql_execute) {
        echo "<script>alert('Data inserted successfully')</script>";
        echo "<script>window.open('special_login.php','_self')</script>";
        } else {
        die(mysqli_error($con)); // Check if there's any error message
        }

    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPAdmin registration</title>

    <!-- bootsrap css link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" 
    crossorigin="anonymous">

    <!-- font awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" 
    crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="icon" href="../images/newlogo.jpg" type="image/icon type">

    <style>
        body{
            overflow: hidden;
        }
    </style>

</head>
<body>
    <div class="container-fluid m-3">
        <h2 class="text-center mb-5">SPAdmin Registration</h2>

        <div class="row d-flex justify-content-center">
            <div class="col-lg-6 col-xl-5">
                <img src="../images/regi.jpg" alt="Admin Registration" class="img-fluid">
            </div>
            <div class="col-lg-6 col-xl-4">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="form_outline mb-4">
                        <label for="userid" class="form-label">UserID</label>
                        <input type="text" id="userid" name="userid" placeholder="Enter your userID" required="required" class="form-control">
                    </div>
                    <div class="form_outline mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required="required" class="form-control">
                    </div>
                    <div class="form_outline mb-4">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required="required" class="form-control">
                    </div>
                    <div class="form_outline mb-4">
                        <label for="special_character" class="form-label">spCharacter</label>
                        <input type="text" id="special_character" name="special_character" placeholder="Enter special characters" required="required" class="form-control">
                    </div>

                    <div>
                        <input type="submit" class="bg-info py-2 px-3 border-0" name="special_registration" value="Register">
                        <p class="small fw-bold mt-2 pt-1">Already have an account? <a href="special_login.php" class="link-danger">Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
