<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAvatarRequest;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    public function update(UpdateAvatarRequest $request)
    {
        //            取得上傳檔案             儲存於指定資料夾
        // $path = $request->file('avatar')->store('public/avatars');
        // $path = $request->file('avatar')->store('avatars', 'public');
        $path = Storage::disk('public')->put('avatar', $request->file('avatar'));

        if ($oldAvatar = $request->user()->avatar) {
            // 刪除 public 目錄下的檔案
            Storage::disk('public')->delete($oldAvatar);
        }

        // 更新資料庫
        auth()->user()->update(['avatar' => $path]);
        return redirect(route('profile.edit'))->with('message', 'Avatar updated successfully');
    }
}
