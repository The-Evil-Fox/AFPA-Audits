<?php

require_once('config/dbConnection.php');
require_once('config/reqUser.php');
require_once('config/roles.php');

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
  <link rel="stylesheet" href="stylesheets/interface.css">
  <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
  <title>AFPA: Formation professionnelle</title>
</head>
<body>
  <?php require_once('config/header.php'); ?>
  <?php require_once('config/sidebar.php'); ?>
  <!-- Content window ( with example home content ) -->
  <div class="content" id="content">
    <div class="card">
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
    </div>
    <div class="card">
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
    </div>
    <div class="card">
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
    </div>
    <div class="card">
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
    </div>
  </div>
  <!-- Content window end -->
  <script src="scripts/interface.js"></script>
  <script src="scripts/contentGenerator.js"></script>
  <script src="scripts/setAvatar.js"></script>
  <script src="scripts/autoEvaluation.js"></script>
  <noscript>Votre navigateur ne supporte pas Javascript !</noscript>
</body>
</html>
