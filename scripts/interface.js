MyFunctions = {

  showError: function(container, xhr) {
    
    if(container.className == "content backgroundImage") {

      container.className = "content";

    }

    container.innerHTML = `
      <div class='errorContainer'>
          <img src='assets/error.png'>`
          + xhr.status + ` ` + xhr.statusText + 
      `</div>`;

  }

}

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

            MyFunctions.showError(xhr);
              
          }

      });

  });

}

function bugReportForm() {

  if(document.getElementById('bugReportContainer') == null) {

  bugReportFormContainer = document.createElement('DIV');
  bugReportFormContainer.id = "bugReportContainer";
  bugReportFormContainer.innerHTML = `
  <div id="bugReportFormContainer">
    <h3>Rapport de bug</h3>
    <textarea id="bugDescription" cols="60" rows="5" placeholder="Veuillez décrire le bug rencontré et ce que vous faisiez lorsqu'il est apparu..."></textarea>
    <h4>Un code ou un message d'erreur est-il apparu ?</h4>
    <div class="radioboxContainer">
      <div class="radiobox">
        <input type="radio" id="errorCodeOui" name="errorCode" value="Oui" onchange="showTextAreaErrorCode(this.value)">
        <label for="errorCodeOui">Oui</label>
      </div>
      <div class="radiobox">
        <input type="radio" id="errorCodeNon" name="errorCode" value="Non" onchange="showTextAreaErrorCode(this.value)">
        <label for="errorCodeNon">Non</label>
      </div>
    </div>
    <textarea cols="60" rows="5" id="errorCodeInput" placeholder="Veuillez insérer le code ou le message d'erreur reçu..."></textarea>
    <div class="buttonsContainer">
      <button class="greenButton" onclick="sendBugReport()"><i class="fas fa-check"></i></button>
      <button onclick="bugReportForm()" class="redButton"><i class="fas fa-times"></i></button>
    </div>
    <div id="bugReportNotification"></div>
  </div>`;

  document.body.style.overflow = "hidden";
  document.body.insertBefore(bugReportFormContainer, document.body.firstChild);

  } else {

    bugReportContainer = document.getElementById('bugReportContainer');
    bugReportContainer.remove();
    document.body.style.overflow = "auto";

  }

}

function showTextAreaErrorCode(radiovalue) {

  let errorCodeInput = document.getElementById('errorCodeInput');
  
  if(radiovalue == "Oui") {

    errorCodeInput.style.display = "inline-block";

  } else {

    errorCodeInput.style.display = "none";

  }

}

function sendBugReport() {

  let bugReportNotification = document.getElementById('bugReportNotification');
  let bug = document.getElementById('bugDescription').value;
  let errorCode = document.getElementById('errorCodeInput').value;

  if(bug == "") {

    bugReportNotification.innerHTML = "Veuillez insérer le bug rencontré !";
    bugReportNotification.style.color = "rgb(255, 120, 27)";

    if(bugReportNotification.style.display == "block") {

      return;

    }

    bugReportNotification.style.display = "block";

    setTimeout(() => {

      bugReportNotification.style.display = "none";
      bugReportNotification.innerHTML = "";

    }, 2500);

    return;

  }

  if(!$("input[name='errorCode']").is(':checked')) {

    bugReportNotification.innerHTML = "Veuillez cocher si oui ou non vous avez reçu une code d'erreur !";
    bugReportNotification.style.color = "rgb(255, 120, 27)";
    
    if(bugReportNotification.style.display == "block") {

      return;

    }
    
    bugReportNotification.style.display = "block";

    setTimeout(() => {

      bugReportNotification.style.display = "none";
      bugReportNotification.innerHTML = "";

    }, 2500);

    return;

  }

    let radioboxValue = $("input[name='errorCode']:checked").val();
    
    if(radioboxValue == "Oui") {

      if(errorCode.length < 5) {

        bugReportNotification.innerHTML = "Veuillez insérer le code ou le message d'erreur reçu !";
        bugReportNotification.style.color = "rgb(255, 120, 27)";

        if(bugReportNotification.style.display == "block") {

          return;

        }
        
        bugReportNotification.style.display = "block";

        setTimeout(() => {

          bugReportNotification.style.display = "none";
          bugReportNotification.innerHTML = "";

        }, 2500);

        return;
        
      }

      dataSend = {

        bug,
        errorCode

      };

    } else {

      dataSend = {

        bug

      };

    }

    $.ajax({

      type: 'POST',
      url: 'traitements/traitementBugReport.php',
      data: dataSend,
      dataType: 'text',
      success: function(data) {
          
          if(data == "Acces refusé ! Veuillez vous connectez !") {

              window.location.replace('index.php');
              return;
              
          }

          bugReportNotification.style.display = "block";
          bugReportNotification.innerHTML = data;

          if(data == "Votre rapport de bug a bien été reçu !") {

            document.getElementById('bugDescription').value = "";
            bugReportNotification.style.color = "green";
            $("input[name='errorCode']").removeAttr("checked");

            if(document.getElementById('errorCodeInput').value !== "") {

              document.getElementById('errorCodeInput').value = "";
              
            }

          }
      
      },
      
      error: function(xhr, textStatus, error) {

        bugReportNotification.style.display = "block";

        MyFunctions.showError(bugReportNotification, xhr);
          
      }

  });

}