<?php

require_once('config/dbConnection.php');
require_once('config/roles.php');

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="stylesheets/login.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
  <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
  <title>AFPA: Formation professionnelle</title>
</head>
<body>
  <div class="login-page">
    <div class="form">
      <form class="login-form" id="form-login">
          <h3><a class="navbar-brand" href="<?= $_SERVER['PHP_SELF']; ?>" title="logo AFPA">
              <img src="assets/logoAFPA.png" alt="Logo AFPA">
          </a></h3>
          <div id="error-message"><?php if(isset($errorMsg) && !empty($errorMsg)) { echo $errorMsg; } ?></div>
          <input type="text" placeholder="Adresse email" id="emailInput"/>
          <input type="password" placeholder="Mot de passe" id="passwordInput"/>
          <button>Connexion</button>
      </form>
      <button class="btn-2" onclick="promptEmail()">Mot de passe oublié ?</button>
    </div>
  </div>
  <script src="scripts/login.js"></script>
</body>
</html>