<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\ImageRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
    protected $imageRepository;

    public function __construct(ImageRepositoryInterface $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function index()
    {
        try {
            $galleries = $this->imageRepository->getAllImages();
            return view('galleries.index', compact('galleries'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve images: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve images.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create()
    {
        return view('galleries.create');
    }

    public function store(Request $request)
    {
        try {
            $this->imageRepository->storeImages($request);
            return redirect()->route('galleries.index')->with('success', 'Images uploaded successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to store images: ' . $e->getMessage());
            return redirect()->route('galleries.create')->withErrors('Failed to upload images.');
        }
    }

    public function edit($id)
    {
        try {
            $gallery = $this->imageRepository->getImageById($id);
            return view('galleries.edit', compact('gallery'));
        } catch (ModelNotFoundException $e) {
            Log::error('Image not found: ' . $e->getMessage());
            return redirect()->route('galleries.index')->withErrors('Image not found!');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve image for editing: ' . $e->getMessage());
            return redirect()->route('galleries.index')->withErrors('Failed to retrieve image for editing!');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->imageRepository->updateImage($request, $id);
            return redirect()->route('galleries.index')->with('success', 'Image updated successfully!');
        } catch (ModelNotFoundException $e) {
            Log::error('Image not found for update: ' . $e->getMessage());
            return redirect()->route('galleries.index')->withErrors('Image not found for update!');
        } catch (\Exception $e) {
            Log::error('Failed to update gallery: ' . $e->getMessage());
            return redirect()->route('galleries.edit', $id)->withErrors('Failed to update gallery!');
        }
    }

    public function destroy($id)
    {
        try {
            $this->imageRepository->deleteImage($id);
            return redirect()->route('galleries.index')->with('success', 'Image deleted successfully!');
        } catch (ModelNotFoundException $e) {
            Log::error('Image not found for deletion: ' . $e->getMessage());
            return redirect()->route('galleries.index')->withErrors('Image not found for deletion!');
        } catch (\Exception $e) {
            Log::error('Failed to delete gallery: ' . $e->getMessage());
            return redirect()->route('galleries.index')->withErrors('Failed to delete image!');
        }
    }
}
