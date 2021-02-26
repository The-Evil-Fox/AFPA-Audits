function showContent(category) {
    
    $(document).ready(function() {

        let contentWindow = document.getElementById('content');

        if(typeof(autoEvalKeyboardControlsActive) !== "undefined") {

            removeControls();

        }

        if(category == "home") {

            contentWindow.style.opacity = 0;
            contentWindow.className = "content backgroundImage";
            contentWindow.innerHTML = "";
            contentWindow.style.opacity = 1;
            return;

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

            },

            error: function(xhr, textStatus, error){

                alert(error);

            }

        });

    });

}