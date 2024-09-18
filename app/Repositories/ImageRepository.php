<?php

namespace App\Repositories;

use App\Models\ImageData;
use Illuminate\Http\Request;
use Spatie\Image\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use App\Repositories\Contracts\ImageRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Exception;

class ImageRepository implements ImageRepositoryInterface
{
    public function getAllImages()
    {
        try {
            return ImageData::all();
        } catch (Exception $e) {
            Log::error('Failed to retrieve images: ' . $e->getMessage());
            throw new \Exception('Failed to retrieve images.');
        }
    }

    public function storeImages(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'tags' => 'nullable|string',
        ]);

        $tags = $request->input('tags');

        try {
            foreach ($request->file('images') as $key => $image) {
                $imageName = time() . '_' . $key . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('storage/images/' . $imageName);
                $image->move(public_path('storage/images'), $imageName);

                Image::load($destinationPath)
                    ->width(800)
                    ->height(600)
                    ->save();

                $optimizerChain = OptimizerChainFactory::create();
                $optimizerChain->optimize($destinationPath);

                ImageData::create([
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'image_url' => 'images/' . $imageName,
                    'tags' => $tags,
                ]);
            }
        } catch (Exception $e) {
            Log::error('Failed to store images: ' . $e->getMessage());
            throw new \Exception('Failed to store images.');
        }
    }

    public function getImageById($id)
    {
        try {
            return ImageData::findOrFail($id);
        } catch (Exception $e) {
            Log::error('Image not found: ' . $e->getMessage());
            throw new \Exception('Image not found.');
        }
    }

    public function updateImage(Request $request, $id)
    {
        $request->validate([
            'tags' => 'nullable|string',
            'title' => 'nullable|string',
        ]);

        try {
            $gallery = ImageData::findOrFail($id);
            $gallery->update([
                'title' => $request->input('title'),
                'tags' => $request->input('tags'),
            ]);
        } catch (Exception $e) {
            Log::error('Failed to update image: ' . $e->getMessage());
            throw new \Exception('Failed to update image.');
        }
    }

    public function deleteImage($id)
    {
        try {
            $gallery = ImageData::findOrFail($id);
            $gallery->delete();
        } catch (Exception $e) {
            Log::error('Failed to delete image: ' . $e->getMessage());
            throw new \Exception('Failed to delete image.');
        }
    }
}
