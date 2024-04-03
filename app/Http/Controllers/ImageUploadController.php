<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|image|mimes:png,jpg|max:500',
                'caption' => 'nullable|string|max:255',
            ]);

            $image = $request->file('file');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('images', $imageName, 'public');

            $newImage = new Image();
            $newImage->path = $path;
            $newImage->name = $imageName;
            $newImage->format = $image->getClientOriginalExtension();
            $newImage->user_id = Auth::id();
            $newImage->size = $image->getSize();
            $newImage->caption = $request->input('caption');
            $newImage->save();

            return response()->json([
                'location' => Storage::url($path),
                'caption' => $request->input('caption')
            ]);
        } catch (\Exception $e) {
            Log::error('Error uploading image: ' . $e->getMessage());
            return response()->json(['error' => 'Error uploading image'], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $request->validate([
                'imagePath' => 'required|string|max:255',
            ]);

            $imagePath = $request->input('imagePath');

            $image = Image::where('path', $imagePath)->first();

            if (!$image) {
                return response()->json(['error' => 'Image not found'], 404);
            }

            if ($image->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            Storage::disk('public')->delete($image->path);
            $image->delete();

            return response()->json(['success' => 'Image deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting image: ' . $e->getMessage());
            return response()->json(['error' => 'Error deleting image: ' . $e->getMessage()], 500);
        }
    }
}
