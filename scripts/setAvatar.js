function setAvatar() {
    
    let useravatar = document.getElementById('avatar').src;
    let avatar = document.getElementById('avatar');
    let avatarResponsive = document.getElementById('avatarResponsive');

    $('#inputAvatar').click();
    $('#inputAvatar').on('change', function() {
        
        // Detects if a image is selected by the user and send the form if it's true

        let userfile = $(this).val();
        if(userfile) {
            
            $('#formAvatar').submit();
            
        }
    
    });
    
    $("#formAvatar").on('submit', function(e) {
        
        e.preventDefault();

        // Shows a loading image instead of the avatar during the processing of the new avatar

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

                myFunctions.checkAuthentifiedUser(data);

                /* If ajax got a message saying there is a error with the new avatar
                   Shows the error in a alert() box and set the avatar to the previous one
                */
                
                if(data.includes("Erreur:")) {
                    
                    let error = data.replace("Erreur:", "");
                    avatar.setAttribute('src', useravatar);
                    alert(error);
                
                // Else refresh the page to show the new avatar

                } else {

                    document.location.reload();
                    
                }
    
            },

            error: function(xhr, textStatus, error){

                myFunctions.showError(contentWindow, xhr);
                
            }
    
        });

    });
  
}