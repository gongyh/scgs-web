@extends('layouts.app')

@section('style')
<style>
input:focus, textarea:focus{
  outline: none;
}
  /*Loading Gif*/
.loader {
    margin: auto;
    border-top-color: #3498db;
    -webkit-animation: spinner 1s linear infinite;
    animation: spinner 1s linear infinite;
  }
  
  @-webkit-keyframes spinner {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
  }
  
  @keyframes spinner {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  

.text-green-500 { color: #48bb78; }

.text-red-500 { color: #f56565; }
  
/*Snack bar style */
.paper-snackbar {
    transition-property: opacity, bottom, left, right, width, margin, border-radius;
    transition-duration: 0.5s;
    transition-timing-function: ease;
    font-size: 14px;
    min-height: 14px;
    background-color: #4caf50;
    border-radius: 4px;
    position: absolute;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
    line-height: 22px;
    padding: 18px 24px;
    bottom: 0px;
    opacity: 0;
  
  }

  .error-snack{
    background-color: #ff5252 !important;
  }
  
  @media (min-width: 640px) {
    .paper-snackbar {
      /*
      Desktop:
        Single-line snackbar height: 48 dp tall
        Minimum width: 288 dp
        Maximum width: 568 dp
        2 dp rounded corner
        Text: Roboto Regular 14 sp
        Action button: Roboto Medium 14 sp, all-caps text
        Default background fill: #323232 100%
      */
  
      min-width: 288px;
      max-width: 568px;
      display: inline-flex;
      border-radius: 2px;
      margin: 24px;
      bottom: -100px;
      
    }
  }
  
  @media (max-width: 640px) {
    .paper-snackbar {
    /*
    Mobile:
      Single-line snackbar height: 48 dp
      Multi-line snackbar height: 80 dp
      Text: Roboto Regular 14 sp
      Action button: Roboto Medium 14 sp, all-caps text
      Default background fill: #323232 100%  
    */
      left: 0px;
      right: 0px;
    }
  }
  
  .paper-snackbar .action {
    background: inherit;
    display: inline-block;
    border: none;
    font-size: inherit;
    text-transform: uppercase;
    color: #FFFFFF;
    margin: 0px 0px 0px 24px;
    padding: 0px;
    min-width: min-content;
  }
</style>
@endsection

@section('content')
<div class="container m-auto">
    <div class="row">
      <div class="col-md-3"></div>
      <div class="col-md-6">
      <h1>Contact Us</h1>
      <form action="/contact" method="post" class="needs-validation" novalidate>
        @csrf
        <div class="">
          <div class="">
            <div class="">
             <p>
              <label class="block uppercase tracking-wide text-grey-900 text-xs font-bold mb-2" for="name">
                Your Name *
              </label>
              <br>
              <input name="name" class="rounded-lg appearance-none block w-full bg-gray-200 focus:border-gray-900 text-grey-900 border-gray-200 border-2 rounded" id="name" type="text" placeholder="Your name" required>
              <p id="name-label-success" class="text-green-500"></p>
              <p id="name-label-error" class="text-red-500"></p>
             </p>
            </div>
            <div class="">
              <p>
              <label class="block uppercase tracking-wide text-grey-900 text-xs font-bold mb-2" for="email">
                Your Email *
              </label>
              <br>
              <input name="email" class="rounded-lg appearance-none block w-full bg-gray-200 focus:border-gray-900 text-grey-900 border-2 border-gray-200 rounded" id="email" type="email" placeholder="Email address" required>
              </p>
              <p id="email-label-success" class="text-green-500"></p>
              <p id="email-label-error" class="text-red-500"></p>
            </div>
          </div>
          <div class="">
            <div class="">
              <p>
              <label class="block uppercase tracking-wide text-grey-900 text-xs font-bold mb-2" for="message">
                Your Message *
              </label>
              <br>
              <textarea name="message" class="rounded-lg appearance-none block w-full bg-gray-200 focus:border-gray-900 text-grey-900 border-2 border-gray-200 rounded py-3" rows="5" id="message" placeholder="" required minlength="12"></textarea>
              </p>
              <p id="message-label-success" class="text-green-500"></p>
              <p id="message-label-error" class="text-red-500"></p>
            </div>
          </div>
          <div class="">
            <div class="">
              <p>
              <button id="submit" type="submit" class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded-lg w-48 m-auto">
                <span id="send-text" class="">Send</span>
              </button>
              </p>
              <div id="loader" class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-6 w-6 hidden"></div>
            </div>
          </div>
        </div>
      </form>
    </div>
   </div>
</div>
@endsection

@section('script')
<script>
  var createSnackbar = (function() {
  // Any snackbar that is already shown
  var previous = null;
  
  
    return function(message, actionText, action, error) {
      if (previous) {
        previous.dismiss();
      }
      var snackbar = document.createElement('div');
      snackbar.className = 'paper-snackbar';
      error ? snackbar.className += ' error-snack' : '';
      snackbar.dismiss = function() {
        this.style.opacity = 0;
      };
      var text = document.createTextNode(message);
      snackbar.appendChild(text);
      if (actionText) {
        if (!action) {
          action = snackbar.dismiss.bind(snackbar);
        }
        var actionButton = document.createElement('button');
        actionButton.className = 'action';
        actionButton.innerHTML = actionText;
        actionButton.addEventListener('click', action);
        snackbar.appendChild(actionButton);
      }
      setTimeout(function() {
        if (previous === this) {
          previous.dismiss();
        }
      }.bind(snackbar), 50000);
      
      snackbar.addEventListener('transitionend', function(event, elapsed) {
        if (event.propertyName === 'opacity' && this.style.opacity == 0) {
          this.parentElement.removeChild(this);
          if (previous === this) {
            previous = null;
          }
        }
      }.bind(snackbar));

      
      
      previous = snackbar;
      document.body.appendChild(snackbar);
      // In order for the animations to trigger, I have to force the original style to be computed, and then change it.
      getComputedStyle(snackbar).bottom;
      snackbar.style.bottom = '0px';
      snackbar.style.opacity = 1;
    };
  })();


    // Example starter JavaScript for disabling form submissions if there are invalid fields

    const errorColor = ['border-red-500', 'focus:border-red-600'];
    const successColor = ['border-green-500', 'focus:border-green-600'];

    function cleanForm(element){
      element.classList.remove(...errorColor)
      element.classList.remove(...successColor)
      document.getElementById(element.name + '-label-error').innerHTML = ''
      document.getElementById(element.name + '-label-success').innerHTML = ''
    }

    function invalidField(element) {
      element.classList.add(...errorColor)
      element.classList.remove(...successColor)
      document.getElementById(element.name + '-label-error').innerHTML = 'Please fill in the field ' + element.id
      document.getElementById(element.name + '-label-success').innerHTML = ''
    }

    function validMessage(element) {
      element.classList.add(...successColor)
      element.classList.remove(...errorColor)
      document.getElementById(element.name + '-label-success').innerHTML = 'Valid field'
      document.getElementById(element.name + '-label-error').innerHTML = ''
    }

    function invalidEmail(element) {
      element.classList.add(...errorColor)
      element.classList.remove(...successColor)
      document.getElementById(element.name + '-label-error').innerHTML = 'Email must be valid'
      document.getElementById(element.name + '-label-success').innerHTML = ''
    }

    function invalidMessage(element) {
      element.classList.add(...errorColor)
      element.classList.remove(...successColor)
      document.getElementById(element.name + '-label-error').innerHTML = 'The message must have at least 12 characters'
      document.getElementById(element.name + '-label-success').innerHTML = ''
    }

    function validField(element) {
      switch (element.name) {
        case 'email':
          ValidateEmail(element.value) ? validMessage(element) : invalidEmail(element);
          break;
        case 'message':
          element.value.length > 12 ? validMessage(element) : invalidMessage(element);
          break;
        case 'name':
          validMessage(element);
          break;
      }

    }

    function ValidateEmail(mail) {
      if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{1,20})+$/.test(mail)) {
        return (true)
      }
      return (false)
    }

    //Loading Gif
    const loader = document.getElementById('loader');
    //Send Text
    const sendText = document.getElementById('send-text');

    function showLoader(){
      loader.classList.remove('hidden');
      sendText.classList.add('hidden');
    }
    function hideLoader(){
      loader.classList.add('hidden');
      sendText.classList.remove('hidden');
    }

    (function() {
      'use strict';
      window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            event.preventDefault();
            event.stopPropagation();
            const fields = [].slice.call(document.querySelectorAll('input, textarea'), 1);
            fields.forEach(element => {
              element.addEventListener('input', function(e) {
                e.srcElement.value == '' ? invalidField(element) : validField(element);
              })

              element.value == '' ? invalidField(element) : validField(element);
            });
            
            if (form.checkValidity() === false) {
              return 
            }else{
              showLoader();
              const data = fields.reduce((acc, cur) => ({ ...acc, [cur.name]: cur.value }), {})
              
              axios.post(form.action, data)
                .then(res => {
                  console.log(res);
                  createSnackbar('Your message has been sent!', null);
                  form.reset();
                  fields.forEach(element => {
                    cleanForm(element);
                  })
                  hideLoader()
                }).catch(err => {
                    console.log(err);
                    createSnackbar('Message could not be sent!', null, null, true);
                    hideLoader()
                })
            }

          }, false);
        });
      }, false);
    })();
</script>
@endsection
