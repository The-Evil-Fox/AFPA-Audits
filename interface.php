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
  <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
  <link rel="stylesheet" href="stylesheets/interface.css">
  <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
  <!-- No cache -->
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <title>Afpa: outil qualité</title>
</head>
<body>
  <?php require_once('config/header.php'); ?>
  <?php require_once('config/sidebar.php'); ?>
  <!-- Content window ( with home background image ) -->
  <div class="content backgroundImage" id="content">
    <div id="titleContainer">
      <h3>Outil qualité digitalisé</h3>
    </div>
  </div>
  <div id="bugButtonContainer">
    <button id="bugButton" onclick="bugReportForm()"><i class='fas fa-bug'></i></button>
  </div>
  <!-- Content window end -->
  <script src="scripts/interface.js"></script>
  <script src="scripts/showResults.js"></script>
  <script src="scripts/contentGenerator.js"></script>
  <script src="scripts/setAvatar.js"></script>
  <script src="scripts/questionnaires.js"></script>
  <script src="scripts/charts.js"></script>
  <?php if(isAdmin($userInfos['Role']) || isAuditeur($userInfos['Role'])) { ?>
    <script src="scripts/administration.js"></script>
  <?php } ?>
  <noscript>Votre navigateur ne supporte pas Javascript !</noscript>
</body>
</html>