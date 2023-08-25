<?php

namespace App\Http\Controllers;

use App\Http\Requests\StrangerProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function index()
    {
        $users = User::with(['roles'])->get();
        $roles = Role::all();


        return view('permission.index', ['users' => $users, 'roles' => $roles]);
    }

    public function show(User $user)
    {
        $role = $user->roles;

        $roles = Role::all();
        
        return view('permission.show', ['user' => $user, 'role' => $role, 'roles' => $roles]);
    }

    public function update(StrangerProfileUpdateRequest $request, User $user)
    {
        if(!array_key_exists('role', $request->validated())){
            $user->fill($request->validated());
            $user->save();
        }
        else{
            $user->removeRole($user->roles()->get()[0]);
            $user->assignRole($request->validated()['role']);
        }       
        
        return back();
        return Redirect::route('permissions.show', $user->id)->with('status', 'profile-updated');
    }

    public function destroy(Request $request, User $user)
    {
        $user->delete();

        return PermissionController::index();
    }

}
