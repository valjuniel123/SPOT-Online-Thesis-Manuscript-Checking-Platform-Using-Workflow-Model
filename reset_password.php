<?php
session_start ();
session_destroy();
include('database.php');
if (isset($_GET["key"]) && isset($_GET["email"]) && isset($_GET["action"]) 
&& ($_GET["action"]=="reset") && !isset($_POST["action"])){
  $key = $_GET["key"];
  $email = $_GET["email"];
    //Verifying email and key
    $query = "SELECT * from acc_forgot
                WHERE forgot_email = '$email' AND `forgot_key`='$key'"; //any select query code
    $sql = mysqli_query($conn, $query) or die ("Account Search Error: ".mysqli_error($conn));
    $row = mysqli_num_rows($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SPOT - Reset</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <script type="text/javascript">
        var check = function() {
            if (document.getElementById('password').value ==
            document.getElementById('repeatPassword').value) {
                document.getElementById("checker").src="img/checked.png";
                document.getElementById("reset").disabled = false;
            } else if(document.getElementById('password').value =="" || document.getElementById('repeatPassword').value == "")
            {
                document.getElementById("checker").src="img/wrong.png";
                document.getElementById("reset").disabled = true;
            }
            else
            {
                document.getElementById("checker").src="img/wrong.png";
                document.getElementById("reset").disabled = true;
            }
        }
    </script>

    <!-- Custom styles for this template-->
    <!--<link rel="stylesheet" href="{{ asset('css/sb-admin-2.min.css')}}" >-->
    
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-danger">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block">
                        <img src="img/batstateu.jpg" class="mt-4 ml-4 d-block" alt="Spartan" width="100%">
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4 font-weight-bold">Reset Account!</h1>
                            </div>
                            <?php if ($row==""){?>
                            <div class="text-center">
                                <h1 class="h6 text-gray-900 mb-4">Account not valid!</h1>
                            </div>
                            <?php }else{ ?>
                            <form class="user" onSubmit="return validate();" method="post" action="controller_reset.php">
                                <div class="form-group">
                                    <input type="hidden" value="<?php echo $email;?>" class="form-control form-control-user" id="InputEmail"
                                        placeholder="Email Address" name="email" required>
                                </div>
                                <div class="form-group row">
                                    <input type="password" class="form-control form-control-user"
                                        id="password" placeholder="Password" name="password" onkeyup='check();' required>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xl-10">
                                        <input type="password" class="form-control form-control-user"
                                            id="repeatPassword" placeholder="Repeat Password" onkeyup='check();' required>
                                    </div>
                                    <div class="col">
                                        <img id="checker" src="" width="50px">
                                    </div>
                                </div>
                                <button type="submit" name="reset" id="reset" class="btn btn-danger btn-user btn-block">
                                    Reset Password
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="login.php">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
<?php
    }
}
?>