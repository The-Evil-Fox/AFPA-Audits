function showContent(category) {
    
    $(document).ready(function() {

        if(typeof(autoEvalKeyboardControlsActive) !== "undefined" && autoEvalKeyboardControlsActive == true) {

            autoEvalKeyboardControlsActive = false;
            document.body.removeEventListener('keydown', autoEvalShortcutsControls);
            page = 1;
        }

        let contentWindow = document.getElementById('content');

        autoEvalShortcutsControls = (e) => {

            keyPressed = e.which || e.keyCode;
        
            questionsNbr = document.getElementById('questionsNumber').value;
        
            if(keyPressed === 37) {
                
                if(page == 1) {
        
                    return false;
            
                }
        
                autoEvalPrevious(page);
        
            }
            
            if(keyPressed === 39) {
                
                if(page == questionsNbr) {
        
                    return false;
            
                }
                
                autoEvalNext(page);
        
            }
        
        }

        if(category == "home") {

            contentWindow.style.opacity = 0;
            contentWindow.className = "content backgroundImage";
            contentWindow.innerHTML = "";
            contentWindow.style.opacity = 1;
            return false;

        }

        $.ajax({

            type: 'POST',
            url: 'traitements/contentGenerator.php',
            data: category,
            dataType: 'text',
            success: function(data) {

                contentWindow.className = "content";

                contentWindow.style.opacity = 0;

                setTimeout(function() {

                    contentWindow.innerHTML = data;
                    contentWindow.style.opacity = 1;

                }, 500)

                if ($('#mobileNavDropdown').attr('class').indexOf('active') > -1) {

                    $('#mobileNavDropdown').removeClass('active');

                }

                if(category == "autoevaluation") {
                    
                    document.body.addEventListener('keydown', autoEvalShortcutsControls);
                    autoEvalKeyboardControlsActive = true;

                }

            },

            error: function(xhr, textStatus, error){

                alert(error);

            }

        });

    });

}