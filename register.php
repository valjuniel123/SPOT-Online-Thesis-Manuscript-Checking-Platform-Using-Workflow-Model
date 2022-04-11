<?php
session_start ();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SPOT - Register</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <script type="text/javascript">
        var check = function() {
            var pass1 = document.getElementById('password');
            var pass2 = document.getElementById('repeatPassword');

            if (pass1.value.length > 5) {
                document.getElementById("message").textContent = "";

                if (document.getElementById('password').value ==
                document.getElementById('repeatPassword').value) {
                    document.getElementById("checker").src="img/checked.png";
                    document.getElementById("registerStudent").disabled = false;
                    document.getElementById("registerAdmin").disabled = false;
                } else {
                    document.getElementById("checker").src="img/wrong.png";
                    document.getElementById("registerStudent").disabled = true;
                    document.getElementById("registerAdmin").disabled = true;
                }
            } else {
                document.getElementById("message").textContent = "Password must have 6 characters or more";
                document.getElementById("checker").src="img/wrong.png";
                document.getElementById("registerStudent").disabled = true;
                document.getElementById("registerAdmin").disabled = true;
            }
        }
    </script>
    <script>
        function registerFunction() {
            var pos = document.getElementById("pos").value;
            var codeUnique = document.getElementById("codeUnique");
            var registerStudent = document.getElementById("registerStudent");
            var registerAdmin = document.getElementById("registerAdmin");
            if(pos>1){
                document.getElementById("uni_code").value = ""
                registerStudent.style.display="none";
                registerAdmin.style.display="block";
            }
            else{
                codeUnique.style.display="block";
                document.getElementById("uni_code").value = ""
                registerStudent.style.display="block";
                registerAdmin.style.display="none";
            }
        }
        function depFunction() {
            var dep = document.getElementById("dep").value;
            
            document.getElementById("uni_code").value = ""
        }
    </script>
    <script>
        function disFunction() {
            var codeUnique = document.getElementById("codeUnique");
            var registerStudent = document.getElementById("registerStudent");
            var registerAdmin = document.getElementById("registerAdmin");
            codeUnique.style.display="block";
            registerStudent.style.display="block";
            registerAdmin.style.display="none";
            registerStudent.disabled="true";
            registerAdmin.disabled="true";
        
        }
    </script>

    <!-- Custom styles for this template-->
    <!--<link rel="stylesheet" href="{{ asset('css/sb-admin-2.min.css')}}" >-->
    
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- UPLOADING FORMATING -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


</head>

<body class="bg-gradient-danger" onload="disFunction()">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block">
                    <img src="img/batstateu.jpg" class="mt-5 pt-5 ml-4 d-block" alt="Spartan" width="100%">
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form class="user" onSubmit="return validate();" method="post" action="controller_register.php">
                                <div class="form-group row">
                                    <div class="col-sm-4 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="FirstName"
                                            placeholder="First Name" name="fname" required>
                                    </div>
                                    <div class="col-sm-4 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="MiddleName"
                                            placeholder="Middle Name" name="mname">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control form-control-user" id="LastName"
                                            placeholder="Last Name" name="lname" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="ID"
                                        placeholder="Employee ID / SR Code" name="uid" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="InputEmail"
                                        placeholder="Email Address" name="email" required>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-5 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            id="password" placeholder="Password" name="password" onkeyup='check();' required>
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="password" class="form-control form-control-user"
                                            id="repeatPassword" placeholder="Repeat Password" onkeyup='check();' required>
                                    </div>
                                    <div class="col-sm-2">
                                        <img id="checker" src="" width="50px">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <p class="small" id="message"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <select class="form-control form-select" aria-label=".form-select-lg example" name="dep" onchange="depFunction()" id="dep" required>
                                        <option value="" disabled selected>Please select your College Department</option>
                                        <option value="1">College of Arts and Sciences</option>
                                        <option value="2">College of Accountancy Business Economics and International Hospitality Management</option>
                                        <option value="3">College of Informatics and Computing Sciences</option>
                                        <option value="4">College of Industrial Technology</option>
                                        <option value="5">College of Engineering</option>
                                        <option value="6">College of Teacher Education</option>
                                      </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control form-select" aria-label=".form-select-lg example" name="pos"  onchange="registerFunction()" id="pos" required>
                                        <option value="" disabled selected>Please select your Position</option>
                                        <option value="1">Researcher</option>
                                        <option value="2">Instructor</option>
                                        <option value="3">Panel</option>
                                        <option value="4">Dean</option>
                                    </select>
                                </div>
                                <div class="form-group row" id="codeUnique">
                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="uni_code"
                                            placeholder="Unique Code" name="uni_code" required>
                                    </div>
                                </div>
                                <button type="submit" name="registerStudent" id="registerStudent" class="btn btn-danger btn-user btn-block">
                                    Register Account
                                </button>
                                <button type="submit" name="registerAdmin" id="registerAdmin" class="btn btn-danger btn-user btn-block">
                                    Register Account
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="forgot.php">Forgot Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="login.php">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>    
    <script>
            $('#uni_code').keyup(function(){
                var uniCode = $('#uni_code').val();
                var posCode = $('#pos').val();
                var depCode = $('#dep').val();
                if(posCode==1){
                    $.ajax({
                        url: 'controller_code_checker.php',
                        data: { uniCode : uniCode, depCode : depCode },
                        dataType:'html',
                        type:'POST',
                        success: function(data) {
                            if(data=="Success") {
                                $('#uni_code').css("color","green");
                                $('#registerStudent').removeAttr('disabled');
                            }
                            else {
                                $('#uni_code').css("color","red");
                                $('#registerStudent').attr('disabled','disabled');
                            }
                        }
                    });
                }
                else if(posCode>1){
                    $.ajax({
                        url: 'controller_code_checker_dean.php',
                        data: { uniCode : uniCode },
                        dataType:'html',
                        type:'POST',
                        success: function(data) {
                            if(data=="Success") {
                                $('#uni_code').css("color","green");
                                $('#registerAdmin').removeAttr('disabled');
                            }
                            else {
                                $('#uni_code').css("color","red");
                                $('#registerAdmin').attr('disabled','disabled');
                            }
                        }
                    });
                }
            });
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>