function setAvatar() {
    
    let avatar = document.getElementById('avatar');
    let useravatar = document.getElementById('avatar').src;
    let avatarResponsive = document.getElementById('avatarResponsive');
    let useravatarResponsive = document.getElementById('avatarResponsive').src;

    $('#inputAvatar').click();
    $('#inputAvatar').on('change', function() {
    
        let userfile = $(this).val();
        if(userfile) {
            
            $('#formAvatar').submit();
            
        }
    
    });
    
    $("#formAvatar").on('submit', function(e) {
        
        e.preventDefault();
        avatar.src = "assets/loading.gif";
        avatarResponsive.src = "assets/loading.gif";
        $.ajax({
            type: 'POST',
            url: 'traitements/traitementAvatar.php',
            data:  new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            
            success: function(data) {
                
                if(data.includes("Erreur:")) {
                    
                    let error = data.replace("Erreur:", "");
                    avatar.setAttribute('src', useravatar);
                    alert(error);
                    
                } else {
                    
                    avatar.setAttribute('src', data);
                    avatarResponsive.setAttribute('src', data);
                    
                }
    
            }
    
        });

    });
  
}