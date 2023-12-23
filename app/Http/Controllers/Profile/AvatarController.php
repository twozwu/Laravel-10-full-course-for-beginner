<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AvatarController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'avatar' => 'image',
            // 'avatar' => 'required|image|max:2048',
        ]);
        dd($request->all());
        // stroe avatar
        return redirect(route('profile.edit'));
    }
}
