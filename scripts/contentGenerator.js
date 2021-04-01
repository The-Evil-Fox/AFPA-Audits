const contentWindow = document.getElementById('content');

function showContent(category) {
    
    $(document).ready(function() {

        myFunctions.showLoading(contentWindow);

        if(typeof(autoEvalKeyboardControlsActive) !== "undefined" && autoEvalKeyboardControlsActive == true) {

            autoEvalKeyboardControlsActive = false;
            document.body.removeEventListener('keydown', autoEvalShortcutsControls);
            page = 1;
        }

        autoEvalShortcutsControls = (e) => {

            keyPressed = e.which || e.keyCode;
        
            if(document.getElementById('questionsNumber') == null) {

                return;

            }

            questionsNbr = document.getElementById('questionsNumber').value;
        
            if(keyPressed === 37) {
                
                if(page == 1) {
        
                    return false;
            
                }
        
                previousQuestion(page);
        
            }
            
            if(keyPressed === 39) {
                
                if(page == questionsNbr) {
        
                    return false;
            
                }
                
                nextQuestion(page);
        
            }
        
        }

        if(category == "home") {

            contentWindow.style.opacity = 0;
            contentWindow.className = "content backgroundImage";
            contentWindow.innerHTML = `
            <div id="titleContainer">
                <h3>Outil qualité digitalisé</h3>
            </div>`;
            contentWindow.style.opacity = 1;
            return false;

        }

        $.ajax({

            type: 'POST',
            url: 'contentGenerators/contentGenerator.php',
            data: category,
            dataType: 'text',
            success: function(data) {

                myFunctions.checkAuthentifiedUser(data);

                if(category == "administration") {

                    document.getElementById('sidebar').innerHTML = data;
                    return;

                }

                contentWindow.className = "content";

                contentWindow.style.opacity = 0;

                setTimeout(function() {

                    contentWindow.innerHTML = data;
                    contentWindow.style.opacity = 1;

                }, 500)

                if ($('#mobileNavDropdown').attr('class').indexOf('active') > -1) {

                    $('#mobileNavDropdown').removeClass('active');

                }

                if(category == "autoevaluation" || category == "auditer") {
                    
                    document.body.addEventListener('keydown', autoEvalShortcutsControls);
                    autoEvalKeyboardControlsActive = true;

                }

            },

            error: function(xhr, textStatus, error) {

                myFunctions.showError(contentWindow, xhr);
                
            }

        });

    });

}