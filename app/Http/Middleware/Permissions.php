<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Closure;

class Permissions
{
    public function handle($request, Closure $next, $data)
    {
        // dd($data);
        if (Auth::guard('admin')->check()) {
            if (Auth::guard('admin')->user()->id == 1) {
                return $next($request);
            }
            if (Auth::guard('admin')->user()->role_id == 0) {
                return redirect()->route('admin.dashboard')->with('unsuccess', "You don't have access to that section");
            }
            if (Auth::guard('admin')->user()->role->permissionCheck($data)) {
                return $next($request);
            } else if (!(new Permission)->checkPermissionExist($data)) {

                if($request->ajax())
                {
                    return response(['errors'=>["Error. Permission: $data is not found in database."]], 401);
                }

                return redirect()->route('admin.dashboard')->with('unsuccess', "Error. Permission: $data is not found in database.");
            }
        }
        // header("location: ".route('admin.dashboard'));
        if($request->ajax())
        {
            return response(['errors'=>["You don't have access to that section."]]);
        }
        return redirect()->route('admin.dashboard')->with('unsuccess', "You don't have access to that section.");

    }
}
