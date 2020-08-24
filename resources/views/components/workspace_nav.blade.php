 <!-- navbar -->
 <div class="workspace-menu shadow p-3 bg-white rounded overflow-auto">
   <div class="border-bottom">
     <div class="user-box"><img src="image/user.jpg" class="user-image" alt="Responsive image"></div>
     @if(Auth::user()->email == 'admin@123.com')
     <div class="user-name">Administor</div>
     @else
     <div class="user-name">{{Auth::user()->name}}</div>
     @endif
   </div>
   <nav class="nav nav-pills nav-fill flex-column">
     @if(Auth::user()->email == 'admin@123.com')
     <a class="nav-item nav-link workspace-nav" id="nav-home-tab" href="/institutions" aria-selected="false">Manage Institutions</a>
     <a class="nav-item nav-link workspace-nav" id="nav-home-tab" href="/species" aria-selected="false">Manage Species</a>
     <a class="nav-item nav-link workspace-nav" id="nav-home-tab" href="/applications" aria-selected="false">Manage Applications</a>
     @endif
     <a class="nav-item nav-link workspace-nav" id="nav-profile-tab" href="/myLab" aria-selected="false">My Labs</a>
     <a class="nav-item nav-link workspace-nav" id="nav-contact-tab" href="/myProject" aria-selected="false">My Projects</a>
   </nav>
 </div>
