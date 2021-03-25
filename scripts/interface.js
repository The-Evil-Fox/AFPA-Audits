$(document).ready(function(){
    $('.nav_btn').click(function(){
      $('.mobile_nav_items').toggleClass('active');
    });
});

function getNavbar(category) {
    
  $(document).ready(function() {

      let sidebarLinks = document.getElementById('sidebarLinks');
      let mobileNavDropdown = document.getElementById('mobileNavDropdown');

      $.ajax({

          type: 'POST',
          url: 'contentGenerators/navbarGenerator.php',
          data: category,
          dataType: 'text',
          success: function(data) {

              if(data == "Acces refusé ! Veuillez vous connectez !") {

                  window.location.replace('index.php');
                  return;
                  
              }

              sidebarLinks.innerHTML = data;
              mobileNavDropdown.innerHTML = data;

          },

          error: function(xhr, textStatus, error){

            alert(xhr.status + " " + xhr.statusText);
              
          }

      });

  });

}