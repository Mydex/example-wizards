(function ($) {

  $( document ).ready(function() {

    // Terms message toggle
    var termsOfServiceToggle = {

      $termsCheckbox        : {},
      $termsMessageDefault  : {},
      $termsMessageConfirm  : {},

      init: function(termsSelector, termsDefault, termsConfirm) {

        this.$termsCheckbox = $(termsSelector);
        this.$termsMessageDefault = $(termsDefault);
        this.$termsMessageConfirm = $(termsConfirm);

        $(termsSelector).click(function() {
          if(this.checked) {
            termsOfServiceToggle.$termsMessageDefault.hide();
            termsOfServiceToggle.$termsMessageConfirm.show();
            if($('#acceptTermsOfService-error').length) {
              $('#acceptTermsOfService-error').remove();
            }
          }else{
            termsOfServiceToggle.$termsMessageConfirm.hide();
            termsOfServiceToggle.$termsMessageDefault.show();
          }
        });
      }
    };

    // Object to validate the alphanumeric only characters in a MydexID.
    // - If other chars are used, the submit button is disabled and the field is marked with an error, and a message displays.
    // - When corrected, the message is removed and submit button is enabled again.
    // - Instatiate using the init function.
    var mydexID_validation = {
      $nameField    : {},
      $btnSubmit    : {},
      $container    : {},
      mydexID_regex : {},
      tMydexID      : '',
      alertMessage  : '<span class="mydex-id-alert error">Your MydexID must consist of only alphanumeric characters (Aa-Zz 0-9)</span>',

      init: function() {
        // Cache jQuery objects.
        this.$nameField = $('#createMydexID');
        this.$btnSubmit = $('#saveMydexID');
        this.$container = $('#createMydexIdContainer');
        // RegEx to find only alphanumeric characters (a-z0-9).
        this.mydexID_regex = new RegExp(/^[a-z0-9]+$/i);
        // Add listeners for keyp and blur on the input.
        this.$nameField.on('keyup blur', function(){
          // Set var for input value.
          mydexID_validation.tMydexID = mydexID_validation.$nameField.val();
          // Check the input value against the regex.
          mydexID_validation.testRegEx();
        });
      },

      testRegEx: function() {
        // Set the regext test result.
        var regexresult = mydexID_validation.mydexID_regex.test(mydexID_validation.tMydexID);
        // Set field to be valid or invalid depending on regex result.
        if(regexresult) {
          this.setValid();
        }else{
          this.setInvalid();
        }
      },

      setValid: function() {
        // Remove the error class and disabled attribute from the form-item, remove the error message.
        this.$btnSubmit.removeAttr('disabled');
        this.$nameField.removeClass('error');
        $('span.mydex-id-alert').remove();
      },

      setInvalid:function() {
        // Disable the form submit button, add the error class and alert to the form-item.
        this.$btnSubmit.attr('disabled', 'disabled');
        this.$nameField.addClass('error');
        if( $('span.mydex-id-alert').length === 0){
          this.$container.append(this.alertMessage);
        }
      }
    };

    var passwordValidation = {

      evaluate: function (password, mydexid) {
        var weaknesses = 0, strength = 100;

        var hasLowercase = /[a-z]+/.test(password);
        var hasUppercase = /[A-Z]+/.test(password);
        var hasNumbers = /[0-9]+/.test(password);
        var hasPunctuation = /[^a-zA-Z0-9]+/.test(password);

        // Lose 5 points for every character less than 6, plus a 30 point penalty.
        if (password.length < 6) {
          strength -= ((6 - password.length) * 5) + 30;
        }

        // Count weaknesses.
        if (!hasLowercase)    { weaknesses++; }
        if (!hasUppercase)    { weaknesses++; }
        if (!hasNumbers)      { weaknesses++; }
        if (!hasPunctuation)  { weaknesses++; }

        // Apply penalty for each weakness (balanced against length penalty).
        switch (weaknesses) {
          case 1:
            strength -= 12.5;
          break;
          case 2:
            strength -= 25;
          break;
          case 3:
            strength -= 40;
          break;
          case 4:
            strength -= 40;
          break;
        }

        // Based on the strength, work out what text should be shown by the password strength meter.
        if (strength < 60) {
          indicatorText = 'weak';
        }
        else if (strength < 70) {
          indicatorText = 'fair';
        }
        else if (strength < 80) {
          indicatorText = 'good';
        }
        else if (strength <= 100) {
          indicatorText = 'strong';
        }
        // Assemble the final message.
        return { strength: strength, indicatorText: indicatorText };
      },

      meter: function(strength, text) {
        // Update strength meter text and width of indicator.
        var _strengthSpan = '<span class="' + text + '">' + text + '</span>';
        $(".password-strength-text").html(_strengthSpan);
        $('.password-indicator .indicator').css('width', strength + '%');
      },

      // Check for match between supplied fields (Can be used for Password and Private Key fields).
      checkMatch: function(mismatch_str, field_id, confirm_id) {
        // Combine the selectors to add events to both at the same time.
        var _selector = field_id + ', ' + confirm_id;
        var $pw = $(field_id);
        var $pwc = $(confirm_id);
        var matchErrorFlag = false; // Set a flag to avoid another jquery.
        var matchConfirmed = '<span id="match-error" class="error">'+mismatch_str+'</span>';
        // Add events.
        $(_selector).on('input keyup blur', function(){
          var _pw =  $pw.val();
          var _pwConfirm = $pwc.val();
          // Check both fields have values.
          if( _pw.length > 0 && _pwConfirm.length > 0) {
            if(_pw !== _pwConfirm) {
              if(!matchErrorFlag) {
                $(matchConfirmed).insertAfter($pwc);
                matchErrorFlag = true;
              }
            }else{
              if(matchErrorFlag) {
                $('#match-error').remove();
                matchErrorFlag = false;
              }
            }
          }
        });
      }
    };

    /*
      VALIDATION CHECKS 
    */

    // set up jquery validate plugin.
    if( $('#pdsCreateForm').length ) {
      $('#pdsCreateForm').validate({
        
        debug : true,

        rules : {
          acceptTermsOfService : {
            required  : true
          },
          createMydexID : {
            required  : true
          },
          newEmail : {
            required  : true,
            email     : true
          },
          newPassword : {
            required  : true,
            minlength : 8,
            passwordNotEqualID : true
          },
          newPasswordConfirm : {
            required  : true
          },
          newPrivateKey : {
            required  : true
          },
          confirmPrivateKey : {
            required  : true
          }
        },
        messages : {
          acceptTermsOfService : {
            required : 'Please accept the Mydex Terms of Service'
          },
          createMydexID : {
            required : 'Please enter a MydexID'
          },
          newEmail : {
            required : 'Please provide an email address',
            email    : 'Please provide a valid email address'
          },
          newPassword : {
            required: 'Please enter a password',
            minlength: 'Your password must be at least 8 characters'
          },
          newPasswordConfirm : {
            required: 'Please confirm your password'
          },
          newPrivateKey : {
            required  : 'Please enter a Private Key'
          },
          confirmPrivateKey : {
            required  : 'Please confirm your Private Key'
          }
        }
      });
    } //End if   

    // Instatiate terms toggle.
    termsOfServiceToggle.init('#acceptTermsOfService', '#termsMessageDefault', '#termsMessageConfirm');

    $('#newPassword').on('keyup blur', function(){
      var mydexID = $('#newMydexId').val();
      var pw = $('#newPassword').val();
      var strength = passwordValidation.evaluate(pw, mydexID);
      passwordValidation.meter(strength.strength, strength.indicatorText);
    });

    $.validator.addMethod('passwordNotEqualID', function (value, element, param) {
      return value != $('#createMydexID').val();
    }, 'Password must not be the same as your MydexID');

    // Check for password match.
    passwordValidation.checkMatch('Passwords must match', '#newPassword', '#newPasswordConfirm');

    // Set validation of MydexID field.
    mydexID_validation.init();

  });

})(jQuery);