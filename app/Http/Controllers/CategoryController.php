<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $category = new Category();

        if($request->has('sortBy')){
            if($request->get('sortDesc') === 'true'){
                $category = $category->orderBy($request->get('sortBy'), 'desc');
            }else{
                $category = $category->orderBy($request->get('sortBy'), 'asc');
            }
        }else{
            $category = $category->orderBy('id', 'desc');
        }

        if($request->has('itemsPerPage')){
            $itemsPerPage = $request->get('itemsPerPage');
        }else{
            $itemsPerPage = 15;
        }

        $search = $request->get('search');

        $category = $category->where('name', 'LIKE', "%$search%")
        ->orWhere('description', 'LIKE', "%$search%")
        ->orderBy('id', 'desc')
        ->paginate($itemsPerPage);

        return [
            'categories' => $category,
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
    }
}
