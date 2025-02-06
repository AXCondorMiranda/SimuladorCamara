<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function  create(){

        if(auth()->user()->role==="ADMIN"){
            return view('admin.register');
        }
        return redirect()->to('/login');
    }

    public function store(Request $request){
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->web_access = ($request->input('web_access') == 'on') ? true : false;
        $user->mobile_access = ($request->input('mobile_access') == 'on') ? true : false;
        $user->save();

        //$user= User::create($request->all());

        return redirect()->to('/examen');
    }


}
