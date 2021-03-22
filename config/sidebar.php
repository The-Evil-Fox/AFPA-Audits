<!--mobile navigation bar start-->
<div class="mobile_nav" id="mobileNav">
    <div class="nav_bar">
        <img src="assets/avatars/<?= $userInfos['Avatar']; ?>" class="mobile_profile_image" alt="Votre avatar" onclick="setAvatar()" id="avatarResponsive">
        <i class="fa fa-bars nav_btn"></i>
    </div>
    <div class="mobile_nav_items" id="mobileNavDropdown">
        <?php if(isAuditeur($userInfos['Role'])) { ?>
            <a onclick="showContent('auditer');" href="#auditer"><i class="fas fa-comments"></i><span>Auditer</span></a>
        <?php } ?>
        <a onclick="showContent('autoevaluation');" href="#autoevaluation"><i class="fas fa-briefcase"></i><span>M'auto-évaluer</span></a>
        <a onclick="showContent('monespace');" href="#monespace"><i class="fas fa-chart-line"></i><span>Mes résultats</span></a>
        <?php if(isAdmin($userInfos['Role']) || isAuditeur($userInfos['Role'])) { ?>
            <a onclick="getNavbar('administration');" href="#administration"><i class="fas fa-toggle-off"></i><span>Administration</span></a>
        <?php } ?>
    </div>
</div>
<!--mobile navigation bar end-->
<!--sidebar start-->
<div class="sidebar" id="sidebar">
    <div class="profile_info">
        <img src="assets/avatars/<?= $userInfos['Avatar']; ?>" class="profile_image" alt="Votre avatar" onclick="setAvatar()" id="avatar">
        <h4><?= $userInfos['Name'] . " " . $userInfos['FirstName']; ?></h4>
        <form id="formAvatar" class="avatar-form" method="POST" enctype="multipart/form-data"><input class="avatar-form" type="file" name="inputAvatar" id="inputAvatar"></form>
    </div>
    <div id="sidebarLinks">
        <?php if(isAuditeur($userInfos['Role'])) { ?>
            <a onclick="showContent('auditer');" href="#auditer"><i class="fas fa-comments"></i><span>Auditer</span></a>
        <?php } ?>
        <a onclick="showContent('autoevaluation');" href="#autoevaluation"><i class="fas fa-briefcase"></i><span>M'auto-évaluer</span></a>
        <a onclick="showContent('monespace');" href="#monespace"><i class="fas fa-chart-line"></i><span>Mes résultats</span></a>
        <?php if(isAdmin($userInfos['Role']) || isAuditeur($userInfos['Role'])) { ?>
            <a onclick="getNavbar('administration');" href="#administration"><i class="fas fa-toggle-off"></i><span>Administration</span></a>
        <?php } ?>
    </div>
</div>
<!--sidebar end-->