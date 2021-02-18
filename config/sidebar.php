<!--mobile navigation bar start-->
<div class="mobile_nav" id="mobile_nav">
    <div class="nav_bar">
        <img src="assets/avatars/<?= $userInfos['Avatar']; ?>" class="mobile_profile_image" alt="Votre avatar" onclick="setAvatar()" id="avatarResponsive">
        <i class="fa fa-bars nav_btn"></i>
    </div>
    <div class="mobile_nav_items">
        <a onclick="showContent('dashboard', true);" href="#tableaudebord"><i class="fas fa-desktop"></i><span>Tableau de bord</span></a>
        <a onclick="showContent('monespace', true);" href="#monespace"><i class="fas fa-user-circle"></i><span>Espace personnel</span></a>
        <a onclick="showContent('autoevaluation', true);" href="#autoevaluation"><i class="fas fa-briefcase"></i></i><span>M'auto-évaluer</span></a>
        <?php if(isAdmin($userInfos['Role'])) { ?>
            <a onclick="showContent('administration', true);" href="#administration"><i class="fas fa-user-lock"></i><span>Espace administration</span></a>
        <?php } ?>
        </div>
</div>
<!--mobile navigation bar end-->
<!--sidebar start-->
<div class="sidebar">
    <div class="profile_info">
        <img src="assets/avatars/<?= $userInfos['Avatar']; ?>" class="profile_image" alt="Votre avatar" onclick="setAvatar()" id="avatar">
        <h4><?= $userInfos['Name'] . " " . $userInfos['FirstName']; ?></h4>
        <form id="formAvatar" class="avatar-form" method="POST" enctype="multipart/form-data"><input class="avatar-form" type="file" name="inputAvatar" id="inputAvatar"></form>
    </div>
    <a onclick="showContent('dashboard', false);" href="#tableaudebord"><i class="fas fa-desktop"></i><span>Tableau de bord</span></a>
    <a onclick="showContent('monespace', false);" href="#monespace"><i class="fas fa-user-circle"></i><span>Espace personnel</span></a>
    <a onclick="showContent('autoevaluation', false);" href="#autoevaluation"><i class="fas fa-briefcase"></i><span>M'auto-évaluer</span></a>
    <?php if(isAdmin($userInfos['Role'])) { ?>
        <a onclick="showContent('administration', false);" href="#administration"><i class="fas fa-user-lock"></i><span>Espace administration</span></a>
    <?php } ?>
</div>
<!--sidebar end-->