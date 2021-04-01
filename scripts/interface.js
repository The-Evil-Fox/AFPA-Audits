// My global reccuring functions used in the differents js files

myFunctions = {

  // Shows a loading in the specified area

  showLoading: function(container) {

    if(container.className == "content backgroundImage") {

      container.className = "content";

    }
    
    container.innerHTML = "<img class='contentLoadingImage' src='assets/loading.gif'>";

  },

  // Shows the ajax error message in a specified area

  showError: function(container, xhr) {
    
    if(container.className == "content backgroundImage") {

      container.className = "content";

    }

    container.innerHTML = `
      <div class='errorContainer'>
          <img src='assets/error.png'>`
          + xhr.status + ` ` + xhr.statusText + 
      `</div>`;

  },

  // Checks if ajax got a unauthorised access error code and head the user to the login page if true

  checkAuthentifiedUser: function(dataGet) {

    if(dataGet == "Acces refusé ! Veuillez vous connectez !") {

      window.location.replace('index.php');
      return;
      
    }

  }

}

// Shows the dropdown of the responsive navbar

$(document).ready(function(){
    $('.nav_btn').click(function(){
      $('.mobile_nav_items').toggleClass('active');
    });
});

// Change the navbars(normal and responsive) if a authorised user click on the button "administration" in the navbar

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

              myFunctions.checkAuthentifiedUser(data);

              sidebarLinks.innerHTML = data;
              mobileNavDropdown.innerHTML = data;

          },

          error: function(xhr, textStatus, error){

            myFunctions.showError(xhr);
              
          }

      });

  });

}

// Shows the bug report form

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

/* Shows the textarea about the errorcode if the user check "Oui" in the radio input
   of the report bug form
*/

function showTextAreaErrorCode(radiovalue) {

  let errorCodeInput = document.getElementById('errorCodeInput');
  
  if(radiovalue == "Oui") {

    errorCodeInput.style.display = "inline-block";

  } else {

    errorCodeInput.style.display = "none";

  }

}

// Send the report bug form to the processing php page

function sendBugReport() {

  let bugReportNotification = document.getElementById('bugReportNotification');
  let bug = document.getElementById('bugDescription').value;
  let errorCode = document.getElementById('errorCodeInput').value;

  /*
     Shows a error message if the user didn't fill the description of the bug
     and abandon the sending of the data to the processing page
  */

  if(bug == "") {

    bugReportNotification.innerHTML = "Veuillez insérer le bug rencontré !";
    bugReportNotification.style.color = "#EE5046";

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

  /*
     Shows a error message if the user didn't check if whether or not he got a error code 
     and abandon the sending of the data to the processing page
  */
 
  if(!$("input[name='errorCode']").is(':checked')) {

    bugReportNotification.innerHTML = "Veuillez cocher si oui ou non vous avez reçu une code d'erreur !";
    bugReportNotification.style.color = "#EE5046";
    
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

      /*
        Shows a error message if the user didn't filled the errorCode textarea
        and abandon the sending of the data to the processing page
      */

      if(errorCode.length < 5) {

        bugReportNotification.innerHTML = "Veuillez insérer le code ou le message d'erreur reçu !";
        bugReportNotification.style.color = "#EE5046";

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
          
          myFunctions.checkAuthentifiedUser(data);

          bugReportNotification.style.display = "block";
          bugReportNotification.innerHTML = data;

          if(data == "Votre rapport de bug a bien été reçu !") {

            document.getElementById('bugDescription').value = "";
            bugReportNotification.style.color = "#A7DB55";
            $("input[name='errorCode']").removeAttr("checked");

            if(document.getElementById('errorCodeInput').value !== "") {

              document.getElementById('errorCodeInput').value = "";
              
            }

          }
      
      },
      
      error: function(xhr, textStatus, error) {

        bugReportNotification.style.display = "block";

        myFunctions.showError(bugReportNotification, xhr);
          
      }

  });

}