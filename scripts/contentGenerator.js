function showContent(category, isResponsive) {
    
    $(document).ready(function() {

        let contentWindow = document.getElementById('content');
        $.ajax({

            type: 'POST',
            url: 'traitements/contentGenerator.php',
            data: category,
            dataType: 'text',
            success: function(data) {

                if(isResponsive == false) {

                    contentWindow.style.opacity = 0;

                    setTimeout(function() {

                        contentWindow.innerHTML = data;
                        contentWindow.style.opacity = 1;

                    }, 500)

                }

                if(isResponsive == true) {

                    contentWindow.innerHTML = data;
                    $('.mobile_nav_items').toggleClass('active');

                }

            },
            error: function(xhr, textStatus, error){
                alert(error);
            }

        });

    });

}