<?php

require_once 'php/fonction.php';

if(isset($_POST['submit'])){
    if(isset($_POST['password']) && isset($_POST['identifiant'])){
        $password = $_POST['password'];
        $identifiant = $_POST['identifiant'];

        $passwordResult = login($identifiant);

        if($password == $passwordResult[0]){
            $_SESSION['logged'] = true;
            header('location: index.php');
        }
    }
}

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="css/mdb.min.css" rel="stylesheet">
    <link href="css/personal/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@1,300&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"
            integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/"
            crossorigin="anonymous"></script>
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"
            integrity="sha256-my/qJggBjG+JoaR9MUSkYM+EpxVkxZRNn3KODs+el74=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.1.1/pdfobject.js"
            integrity="sha256-vZy89JbbMLTO6cMnTZgZKvZ+h4EFdvPFupTQGyiVYZg=" crossorigin="anonymous"></script>

</head>
<body>
<div class="container mt-5 d-flex justify-content-center">
    <div class="col-md-8 d-flex justify-content-center">
        <div class="card w-75">
            <h5 class="card-header info-color white-text text-center py-4">
                <strong>Connexion</strong>
            </h5>

            <!--Card content-->
            <div class="card-body px-lg-5 pt-0">

                <!-- Form -->
                <form class="text-center" style="color: #757575;" action="login.php" method="post">

                    <!-- Email -->
                    <div class="md-form">
                        <input type="text" class="form-control" name="identifiant">
                        <label>Identifiant</label>
                    </div>

                    <!-- Password -->
                    <div class="md-form">
                        <input type="password" class="form-control" name="password">
                        <label>Mot de passe</label>
                    </div>

                    <!-- Sign in button -->
                    <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0 mt-5" type="submit" name="submit">Connexion</button>

                    <!-- Social login -->
                </form>
                <!-- Form -->

            </div>
        </div>
    </div>
</div>
</body>
</html>