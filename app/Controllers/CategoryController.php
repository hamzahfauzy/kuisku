<?php
namespace App\Controllers;
use Category;

class CategoryController
{

    function index()
    {
        $categories = Category::get();
        return $categories;
    }

    function find($id)
    {
        $category = Category::find($id);
        return $category;
    }

    function insert()
    {
        $request = request()->post();
        if($request)
        {
            $validate = [
                'category_name' => ['required','unique:Category']
            ];

            $data = (array) $request;
            if(count(request()->validate($data, $validate)) == 0)
            {
                $category = new Category;
                $category->save([
                    'category_name' => $request->category_name,
                    'category_description' => $request->category_description ? $request->category_description : ''
                ]);
                return $this->index();
            }
            
        }

        return ['status' => false];
    }

    function update()
    {
        $request = request()->post();
        if($request)
        {
            $validate = [
                'category_name' => ['required']
            ];

            $data = (array) $request;
            if(count(request()->validate($data, $validate)) == 0)
            {
                $category = Category::find($request->id);
                $category->save([
                    'category_name' => $request->category_name,
                    'category_description' => $request->category_description ? $request->category_description : ''
                ]);
                return $this->index();
            }
            
        }

        return ['status' => false];
    }

    function delete()
    {
        $request = request()->post();
        if($request)
        {
            Category::delete($request->id);
            return $this->index();
        }

        return ['status' => false];
    }

}