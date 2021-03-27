<?php

require_once('../config/dbConnection.php');
require_once('../config/roles.php');

if(!isset($_SESSION['ID'])) {

    echo "Acces refusé ! Veuillez vous connectez !";
    return;

}

if(isset($_POST['administration'])) {

    $checkRole = $db->prepare('SELECT Role FROM Users WHERE ID = :user');
    $checkRole->bindParam(':user', $_SESSION['ID'], PDO::PARAM_INT);
    $checkRole->execute();

    $countResult = $checkRole->rowCount();

    if($countResult != 1) {

        header('Location: ../logout.php');
        exit();

    }

    $userRole = $checkRole->fetch();

    if(isFormateur($userRole['Role'])) { ?>

        <div class="card text-center">
            <div class="card-content">
                Vous n'êtes pas autorisé à accéder à ce contenu !
            </div>
        </div>

    <?php } else { ?>

        <a onclick="getNavbar('exitAdministration')" href="#exitAdministration"><i class="fas fa-toggle-on"></i><span>Administration</span></a>
        <a onclick="showAdministration('actualites')" href="#actualites"><i class="fas fa-rss-square"></i><span>Fil d'actualité</span></a>
        <a onclick="showAdministration('utilisateurs')" href="#utilisateurs"><i class="far fa-id-card"></i><span>Resultats utilisateurs</span></a>
        <a onclick="showAdministration('documents')" href="#documents"><i class="fas fa-file-pdf"></i><span>Documents</span></a>
        <a onclick="showStats()" href="#statistiques"><i class="fas fa-chart-pie"></i><span>Statistiques globales</span></a>
        <a onclick="showAdministration('autoEvalution')" href="#edit-autoevaluation"><i class="fas fa-screwdriver"></i><span>Autoévaluation</span></a>
        <a onclick="showAdministration('audit')" href="#edit-audit"><i class="fas fa-screwdriver"></i><span>Audit</span></a>
    
    <?php } ?>

<?php } ?>

<?php if(isset($_POST['exitAdministration'])) { 

    $userAccount = $db->prepare('SELECT * FROM Users WHERE ID = :userid');
    $userAccount->bindParam(':userid', $_SESSION['ID'], PDO::PARAM_INT);
    $userAccount->execute();

    $userInfos = $userAccount->fetch(); ?>

    <?php if(isAdmin($userInfos['Role']) || isAuditeur($userInfos['Role'])) { ?>
        <a onclick="getNavbar('administration');" href="#administration"><i class="fas fa-toggle-off"></i><span>Administration</span></a>
    <?php } ?>
    <?php if(isAuditeur($userInfos['Role'])) { ?>
        <a onclick="showContent('auditer');" href="#auditer"><i class="fas fa-comments"></i><span>Auditer</span></a>
    <?php } ?>
    <a onclick="showContent('autoevaluation');" href="#autoevaluation"><i class="fas fa-briefcase"></i><span>M'auto-évaluer</span></a>
    <a onclick="showContent('monespace');" href="#monespace"><i class="fas fa-chart-line"></i><span>Mes résultats</span></a>

<?php } ?>