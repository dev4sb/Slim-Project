<?php
session_start();
if(!empty($_SESSION['user_id']))
{
    header('location:Show_CRUD.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="css/Login.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <script>
        $(document).ready(function() {
            function validateEmail(email) {
                var pattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

                return $.trim(email).match(pattern) ? true : false;
            }

            // $('#email').keyup(function() {
            //     let emailInput = $(this).val();

            //     console.log('eee');
            //     if (emailInput == '') {
            //         $('.val-email').html('This Field Is Required *');
            //         console.log('aaa');
            //     } else {
            //         if (validateEmail(emailInput)) {
            //             $('.val-email').html('');
            //             console.log('bbb');
            //         } else {
            //             $('.val-email').html('Enter Valid Email Address *');
            //             console.log('ccc');
            //         }
            //     }


            // });


            //Login
            $('#submit').on("click", function(e) {
                e.preventDefault();
                let email = $("#email").val().toLowerCase();
                let password = $("#password").val();
                let con1;
                let con2;
                //console.log();

                if (email == 'admin@123' && password == 'admin@123') {
                     con1 = true;
                     con2 = true;
                } 
                else {
                    if (email == '') {
                        $('.val-email').html('This Field Is Required *');

                    } else {
                        if (validateEmail(email)) {
                            $('.val-email').html('');
                             con1 = true;
                            console.log(con1);
                        } else {
                            $('.val-email').html('Enter Valid Email Address *');

                        }
                    }

                    if (password == '') {
                        $('.val-pswd').html('This Field Is Required *');

                    } else {
                        if (password.length >= 8 && password.length <= 20) {
                            $('.val-pswd').html('');
                             con2 = true;
                            console.log(con2);
                        } else {
                            $('.val-pswd').html('Enter password min 8  and max 20 characters');

                        }
                    }

                }




                console.log(con1,con2);
                if (con1 == true && con2 == true) {
                    $.ajax({
                        url: "api/index.php/login",
                        type: "POST",
                        data: {
                            email: email,
                            password: password,
                        },
                        success: function(data) {
                            console.log("success");

                            if(data.status=='true')
                            {
                                $("#suc-msg").html(data.message).fadeIn(); //Success Message
                                $("#err-msg").fadeOut();
                                setTimeout(() => {
                                    $("#suc-msg").fadeOut();
                                }, 4000);
                                console.log('aaaa');
                                window.location.replace("Show_CRUD.php");
                            }
                            else
                            {
                                $("#err-msg").html(data.message).fadeIn(); //Error Message
                                $("#suc-msg").fadeOut();
                                setTimeout(() => {
                                    $("#err-msg").fadeOut();
                                }, 4000);
                            }

                        }
                    });
                }

            });
        });
    </script>
</head>

<body>
    <div class='container py-5 '>

        <form class="card px-2 py-5 p-sm-5" method="POST">

            <!-- Title -->
            <div class="card-title text-center py-4">
                <h1 class=''>LOGIN</h1>
            </div>
            <!-- Email  -->
            <div class="validation val-email"></div>
            <div class="form-group email-bx px-3">
                <i class="far fa-envelope"></i>
                <input type='email' autocomplete="off" name='pswd' id='email' class="email">
            </div>
            <div class="validation val-pswd"></div>
            <!-- Password  -->
            <div class="form-group pswd-bx px-3">
                <i class="fas fa-lock"></i>
                <input type='password' autocomplete="off" name='pswd' id='password' class="password">

            </div>
            <!-- Remember me  -->
            <div class="form-group rmber">

                <input class="" type="checkbox" name=""> Remeber me

            </div>

            <!-- Login Button  -->
            <div class="form-group py-3">
                <input type="submit" class="submit" Value='LOGIN' id="submit">

            </div>

            <div class='form-group  text-center optn m-0'>
                <p>or login with</p>
            </div>

            <div class='form-group  row text-center justify-content-center f-g-bx'>
                <div class="col-10 col-sm-5 facebook py-1 my-2 mx-2 my-sm-0"><img src="https://img.icons8.com/color/19.2/000000/facebook.png" /> Facebook</div>
                <div class="col-10 col-sm-5 google py-1 my-2 mx-2 my-sm-0"> <img src="https://img.icons8.com/color/19.2/000000/google-logo.png" /> Google</div>
            </div>

            <div class='form-group  text-center signin-opt m-0'>
                <p class="m-0">Not a member? <a href="#">Sign up now</a></p>
            </div>

            <!-- <a href="https://icons8.com/icon/59780/facebook">Facebook icon by Icons8</a> -->
        </form>

    </div>
    <div class="err-msg" id="err-msg"></div>
    <div class="suc-msg" id="suc-msg"></div>
</body>

</html>