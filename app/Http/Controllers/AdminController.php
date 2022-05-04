<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function addTag(Request $request)
    {
        // validate request
        $this->validate($request, [
            'tagName' => 'required',
        ]);
        return  Tag::create([
            'tagName' => $request->tagName,
        ]);
    }
    
    public function getTag()
    {
        return  Tag::orderBy('id', 'desc')->get();
    }

    public function editTag(Request $request)
    {
        // validate request
        $this->validate($request, [
            'tagName' => 'required',
            'id' => 'required',
        ]);
        $tag = Tag::find($request->id);
        $tag->tagName = $request->tagName;
        $tag->save();
        return $tag;
    }

    public function deleteTag(Request $request)
    {
        // validate request
        $this->validate($request, [
            'id' => 'required',
        ]);
        $tag = Tag::find($request->id);
        $tag->delete();
        return $tag;
    }

    public function upload(Request $request)
    {
        // validate request
        $this->validate($request, [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $picName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads'), $picName);
        return $picName ;
    }

    public function deleteImage(Request $request)
    {
        // validate request
        $this->validate($request, [
            'imageName' => 'required',
        ]);
        $image = $request->imageName;
        $image_path = public_path('uploads/'.$image);
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        return $image;
    }

    public function addCategory(Request $request)
    {
        // validate request
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required',
        ]);
        return  Category::create([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage,
        ]);
    }

    public function getCategory()
    {
        return  Category::orderBy('id', 'desc')->get();
    }

    public function editCategory(Request $request)
    {
        // validate request
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required',
            'id' => 'required',
        ]);
        $category = Category::find($request->id);
        $category->categoryName = $request->categoryName;
        $category->iconImage = $request->iconImage;
        $category->save();
        return $category;
    }
}
