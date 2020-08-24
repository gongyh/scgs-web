 <!-- navbar -->
 <nav class="nav nav-pills nav-fill flex-column">
   @if(Auth::user()->email == 'admin@123.com')
   <a class="nav-item nav-link workspace-nav" id="nav-home-tab" href="/institutions" aria-selected="false">Manage Institutions</a>
   <a class="nav-item nav-link workspace-nav" id="nav-home-tab" href="/species" aria-selected="false">Manage Species</a>
   <a class="nav-item nav-link workspace-nav" id="nav-home-tab" href="/applications" aria-selected="false">Manage Applications</a>
   @endif
   <a class="nav-item nav-link workspace-nav" id="nav-profile-tab" href="/myLab" aria-selected="false">My Labs</a>
   <a class="nav-item nav-link workspace-nav" id="nav-contact-tab" href="/myProject" aria-selected="false">My Projects</a>
 </nav>
