<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePhotoRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PhotoController extends Controller
{
    /**
     * Show the photo upload form.
     */
    public function edit()
    {
        $user = auth()->user();
        return Inertia::render('Photo/Edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the user's photo.
     */
    public function update(UpdatePhotoRequest $request)
    {
        $user = auth()->user();
        
        // Delete old photo if exists
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }
        
        // Store new photo
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->store('photos', 'public');
            
            $user->update(['photo' => $photoPath]);
        }
        
        return redirect()->route('profile.edit')->with('success', 'Photo updated successfully.');
    }

    /**
     * Remove the user's photo.
     */
    public function destroy()
    {
        $user = auth()->user();
        
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
            $user->update(['photo' => null]);
        }
        
        return redirect()->route('profile.edit')->with('success', 'Photo removed successfully.');
    }

    /**
     * Get the user's photo URL.
     */
    public function getPhoto($userId)
    {
        $user = User::findOrFail($userId);
        
        if (!$user->photo) {
            // Return default avatar if no photo
            return response()->json(['photo_url' => null]);
        }
        
        $photoUrl = Storage::disk('public')->url($user->photo);
        
        return response()->json(['photo_url' => $photoUrl]);
    }
}
