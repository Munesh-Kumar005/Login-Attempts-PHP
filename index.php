<?php include 'db.php'; ?>
<?php
$msg = '';
if (isset($_POST['submit'])) {
    $time = time() - 30;
    $ip_address = getIpAddr();
    $check_login_row = mysqli_fetch_assoc(mysqli_query($conn, "select count(*) as total_count from login_log where try_time>$time
     and  ip_address ='$ip_address'"));
    $total_count = $check_login_row['total_count'];
    if ($total_count == 3) {
        $msg = "To many failed login attempts. Please login after 30 sec";
    } else {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $sql = "select * from users where username='$username' and password='$password'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result)) {
            $_SESSION['IS_LOGIN'] = 'yes';
            mysqli_query($con, "delete from login_log where ip_address='$ip_address'");
            header("location:home.php");
        } else {
            $total_count++;
            $rem_attm = 3 - $total_count;
            if ($rem_attm == 0) {
                $msg = "To many failed login attempts. Please login after 30 sec";
            } else {
                $msg = "Please enter valid login details.<br/>$rem_attm attempts remaining";
            }
            $try_time = time();
            mysqli_query($conn, "insert into login_log(ip_address,try_time) values('$ip_address','$try_time')");
        }
    }
}
function getIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ipAddr = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipAddr = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ipAddr = $_SERVER['REMOTE_ADDR'];
    }
    return $ipAddr;
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <title>Hello, world!</title>
    <style>
        body {
            background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('d.jpg');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            width: 100%;
            height: 100vh;
            position: relative;
        }

        header {
            width: 100%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>

<body>

    <header>
        <div class="container  card w-50 pl-4 pr-4 pt-3 pb-3 m-auto ">
            <div class=" row ">
                <div class=" col-12 ">
                    <h1 class="text-center text-danger">Login Panel With Attempts </h1>
                    <form method="post">
                        <div class=" form-group">
                            <label for="name">Username</label>
                            <input type="text" class="form-control" name="username" aria-describedby="name" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" name="password" id="exampleInputPassword1" required>
                        </div>

                        <button type="submit" name="submit" class="btn btn-success">Submit</button>
                        <div class="form-group text-danger mt-2">
                            <?php

                            echo "<span>$msg</span>";
                            ?>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </header>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>