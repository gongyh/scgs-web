 <!-- navbar -->
 @if(Auth::check())
 <div class="workspace-menu shadow p-3 bg-white rounded overflow-auto">
   <div class="border-bottom">
     <div class="user-box"><img src="{{asset('/images/user.png')}}" class="user-image" alt="Responsive image"></div>
     @if(Auth::user()->name == 'admin')
     <div class="user-name">Administrator</div>
     @else
     <div class="user-name">{{Auth::user()->name}}</div>
     @endif
   </div>
   <nav class="nav nav-pills nav-fill flex-column">
     @if(Auth::user()->name == 'admin')
     <a class="nav-item nav-link rem1 workspace-nav" id="nav-home-tab" href="/workspace/institutions" aria-selected="false">Manage Institutions</a>
     <a class="nav-item nav-link rem1 workspace-nav" id="nav-home-tab" href="/workspace/species" aria-selected="false">Manage Species</a>
     <a class="nav-item nav-link rem1 workspace-nav" id="nav-home-tab" href="/workspace/applications" aria-selected="false">Manage Applications</a>
     <a class="nav-item nav-link rem1 workspace-nav" id="nav-home-tab" href="/workspace/pipelineParams" aria-selected="false">Basic Pipeline Params</a>
     @endif
     <a class="nav-item nav-link rem1 workspace-nav" id="nav-profile-tab" href="/workspace/myLab" aria-selected="false">My Labs</a>
     <a class="nav-item nav-link rem1 workspace-nav" id="nav-contact-tab" href="/workspace/myProject" aria-selected="false">My Projects</a>
     <a class="nav-item nav-link rem1 workspace-nav" id="nav-contact-tab" href="/workspace/runningSample" aria-selected="false">
       Job Status</a>
    <a class="nav-item nav-link rem1 workspace-nav" id="nav-contact-tab" href="/workspace/addSampleFiles" aria-selected="false">
     Upload Files</a>
   </nav>
 </div>
 @else
 <div></div>

 @endif
