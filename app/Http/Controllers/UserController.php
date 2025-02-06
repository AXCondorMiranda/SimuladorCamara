<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\TestType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        if(auth()->user()->role==="ADMIN"){
            $users = User::all();
            return view('admin.users.index',compact('users'));
        }
        return redirect()->to('/login');
    }

    public function create()
    {
        if(auth()->user()->role==="ADMIN"){
            return view('admin.users.create');
        }
        return redirect()->to('/login');
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = $validatedData['password'];
        $user->web_access = ($request->input('web_access') == 'on') ? true : false;
        $user->mobile_access = ($request->input('mobile_access') == 'on') ? true : false;
        $user->save();

        return redirect()->to('/usuario');
    }

    public function edit($id)
    {
        $user = User::find($id);

        return view('admin.users.edit',compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required'
        ]);

        $user = User::findOrFail($id);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        if(($request->input('change_password') == 'on')){
            $user->password = $request->password;
        }

        $user->web_access = ($request->input('web_access') == 'on') ? true : false;
        $user->mobile_access = ($request->input('mobile_access') == 'on') ? true : false;

        $user->save();

        return redirect()->route('usuario.index');
    }

}
