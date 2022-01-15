<?php

namespace App\Http\Controllers;

use App\Http\Requests\Article\StoreRequest;
use App\Http\Requests\Article\UpdateRequest;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $article = Article::join('categories','articles.categorie_id','=','categories.id')
        ->select('articles.id','articles.categorie_id','articles.code','articles.name','categories.name as name_categorie','articles.sale_price','articles.stock','articles.description','articles.state', 'articles.created_at');

        if($request->has('sortBy')){
            if($request->get('sortDesc') === 'true'){
                $article = $article->orderBy($request->get('sortBy'), 'desc');
            }else{
                $article = $article->orderBy($request->get('sortBy'), 'asc');
            }
        }else{
            $article->orderBy('id', 'desc');
        }

        if($request->has('itemsPerPage')){
            $itemsPerPage = $request->get('itemsPerPage');
        }else{
            $itemsPerPage = 15;
        }

        $search = $request->get('search');
        $categoria = $request->get('categoria');

        if(empty($categoria)){
            $article = $article->where('articles.name', 'LIKE', "%$search%")
            ->orWhere('articles.description', 'LIKE', "%$search%")
            ->orWhere('articles.code', 'LIKE', "%$search%")
            ->orWhere('categories.name', 'LIKE', "%$search%")
            ->orderBy('articles.id', 'desc')
            ->paginate($itemsPerPage);
        }else{
            $article = $article->where([
                ['categories.id', $categoria],
                ['articles.name', 'LIKE', "%$search%"]
            ])->orWhere([
                ['categories.id', $categoria],
                ['articles.description', 'LIKE', "%$search%"]
            ])->orWhere([
                ['categories.id', $categoria],
                ['articles.code', 'LIKE', "%$search%"]
            ])->orWhere([
                ['categories.id', $categoria],
                ['categories.name', 'LIKE', "%$search%"]
            ])->orderBy('articles.id', 'desc')
            ->paginate($itemsPerPage);
        }

        $ultimoregistro = Article::latest('id')->first();

        if($ultimoregistro){
            $ultimoregistro = $ultimoregistro->id;
        }else{
            $ultimoregistro = 1;
        }

        return [
            'articles' => $article,
            'categories' => Category::all(),
            'ultimoregistro' => $ultimoregistro
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
        $article = new Article();
        $article->categorie_id = $request->categorie_id;
        $article->code = $request->code;
        $article->name = $request->name;
        $article->sale_price = $request->sale_price;
        $article->stock = $request->stock;
        $article->description = $request->description;
        $article->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $article = Article::findOrFail($id);
        $article->categorie_id = $request->categorie_id;
        $article->code = $request->code;
        $article->name = $request->name;
        $article->sale_price = $request->sale_price;
        $article->stock = $request->stock;
        $article->description = $request->description;
        $article->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();
    }
}
