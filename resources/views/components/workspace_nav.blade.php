 <!-- navbar -->
 <div class="row justify-content-center">
   <div class="col-md-6">
     <nav>
       <div class="nav nav-tabs" id="nav-tab" role="tablist">
         @if(Auth::user()->email == 'admin@123.com')
         <a class="nav-item nav-link workspace-nav" id="nav-home-tab" href="/institutions" aria-selected="false">Manage Institutions</a>
         @endif
         <a class="nav-item nav-link workspace-nav" id="nav-profile-tab" href="/myLab" aria-selected="false">My Labs</a>
         <a class="nav-item nav-link workspace-nav" id="nav-contact-tab" href="/myProject" aria-selected="false">My Projects</a>
       </div>
     </nav>
   </div>
 </div>
