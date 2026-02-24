<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;

class ProfileController extends Controller
{
    // Show profile page
    public function show()
    {
        $userId = session('user_id'); // logged-in user
        $user = Users::findOrFail($userId);

        return view('editor.show', compact('user'));
    }

    // Update profile
    public function update(Request $request)
    {
        $userId = session('user_id'); 
        $user = Users::findOrFail($userId);

        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'language' => 'nullable|string|max:255',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }
            $file = $request->file('avatar');
            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $folder = 'avatars';
            if (!file_exists(public_path($folder))) {
                mkdir(public_path($folder), 0755, true);
            }
            $file->move(public_path($folder), $filename);
            $user->avatar = $folder.'/'.$filename;
        }

        $user->language = $request->language;

        $user->timestamps = false; // disable updated_at
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}