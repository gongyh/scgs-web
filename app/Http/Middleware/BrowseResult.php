<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Projects;
use App\Labs;

class BrowseResult
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
   * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
   */
  public function handle(Request $request, Closure $next)
  {
    $projectId = $request->input('projectID');
    $release_date = Projects::where('id', $projectId)->value('release_date');
    $now = date("Y-m-d");
    strtotime($now) - strtotime($release_date) > 0 ? $is_release = true : $is_release = false;
    if ($is_release) {
      return $next($request);
    } else {
      if (Auth::check()) {
        $user = Auth::user()->name;
        $lab_id = Projects::where('id', $projectId)->value('labs_id');
        strcmp($user, 'admin') == 0 ? $isAdmin = true : $isAdmin = false;
        $isPI = Labs::where([['id', $lab_id], ['principleInvestigator', $user]])->get()->count() > 0;
        if ($isPI || $isAdmin) {
          return $next($request);
        } else {
          return abort('403');
        }
      } else {
        return abort('403');
      }
    }
    return $next($request);
  }
}
