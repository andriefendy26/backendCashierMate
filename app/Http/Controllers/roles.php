<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\roles as RolesModel;

class roles extends BaseController
{
    //
    public function GetRoles(Request $request)
    {
        $roles = RolesModel::all();
        return response()->json($roles);
    }

    public function RegistRoles(Request $request)
    {
        $validateData = $request->validate([
            'role' => 'required',
        ]);
        $roles = RolesModel::create($validateData);

        return response()->json($roles);
    }
}
