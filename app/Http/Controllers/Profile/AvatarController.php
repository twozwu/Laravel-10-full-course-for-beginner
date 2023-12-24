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
        $path = $request->file('avatar')->store('public/avatars');
        // 更新資料庫                        取得檔案放置目錄('底下的資料夾')."/檔案路徑"
        auth()->user()->update(['avatar' => storage_path('app')."/$path"]);
        // C:/Users/Mix/Documents/backend/example-app/storage/app/public/avatars/uoZoVB0MnROQ6pOOCYT33Bi469LJZF8PY0hZJxay.jpg
        dd(auth()->user());
        return redirect(route('profile.edit'))->with('message', 'Avatar updated successfully');
    }
}
