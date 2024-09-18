<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface ImageRepositoryInterface
{
    public function getAllImages();
    public function storeImages(Request $request);
    public function getImageById($id);
    public function updateImage(Request $request, $id);
    public function deleteImage($id);
}
