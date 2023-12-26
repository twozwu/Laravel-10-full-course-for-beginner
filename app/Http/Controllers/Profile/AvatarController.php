<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAvatarRequest;
use Illuminate\Http\Request;

class AvatarController extends Controller
{
    public function update(UpdateAvatarRequest $request)
    {
        //        取得上傳檔案         儲存於指定資料夾
        // $path = $request->file('avatar')->store('public/avatars');
        $path = $request->file('avatar')->store('avatars', 'public');
        // dd($path);
        // 更新資料庫                        取得檔案放置目錄('底下的資料夾')."/檔案路徑"
        auth()->user()->update(['avatar' => $path]);
        dd(auth()->user());
        return redirect(route('profile.edit'))->with('message', 'Avatar updated successfully');
    }
}
