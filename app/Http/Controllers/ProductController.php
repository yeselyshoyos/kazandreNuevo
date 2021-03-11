<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Faker\Provider\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Category::all();
        $rows = DB::table('products')
                ->join('categories', 'products.id_category', '=', 'categories.id')
                ->select('products.*', 'categories.name AS nameCategory')
                ->get();

        return view('products.index', compact('rows', 'categorias'));
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
    public function store(Request $request)
    {
        //las images 2 y 3 no son requeridas
        if ($request->file('img2') == null) {
            $image2 = "";
        }else{

            $image2 = $request->file('img2')->storeAs('public/img',  $request->name.'2'.".jpg");
        }
        if ($request->file('img3') == null) {
            $image3 = "";
        }else{

            $image3 = $request->file('img3')->storeAs('public/img', $request->name.'3'.".jpg");
        }
        #-----------------------------------------------------------------------------
        $request->validate([
            'name'          => 'required',
            'description'   => 'required',
            'price'         => 'required',
            'id_category'   => 'required',
            'stock'         => 'required',
            'img1'          => 'required|image|mimes:jpeg,png,jpg|max:2024',
            'img2'          => 'image|mimes:jpeg,png,jpg|max:2024',
            'img3'          => 'image|mimes:jpeg,png,jpg,|max:2024',

        ]);

        $image1 = $request->file('img1')->storeAs('public/img', $request->name. ".jpg");

        //para que se guarde el campo vacio en la BD



        #------------------------------------------------------------------------------------------------------
        $img1 = Storage::url($image1);
        $img2 = Storage::url($image2);
        $img3 = Storage::url($image3);

        Product::create([
            'name'          => $request['name'],
            'description'   => $request['description'],
            'price'         => $request['price'],
            'id_category'   => $request['id_category'],
            'stock'         => $request['stock'],
            'img1'          => $img1,
            'img2'          => $img2,
            'img3'          => $img3,
        ]);

        return back()->with('mensaje', '¡Producto creado con exito!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return "hola mundo";
/*
        if ($request->file('img1') == null) {
            $image1 = "";
        }else{

            $image1 = $request->file('img1')->storeAs('public/img', $request->name.".jpg");
        }

        if ($request->file('img2') == null) {
            $image2 = "";
        }else{

            $image2 = $request->file('img2')->storeAs('public/img', $request->name. '2'.".jpg");
        }
        if ($request->file('img3') == null) {
            $image3 = "";
        }else{

            $image3 = $request->file('img3')->storeAs('public/img', $request->name.'3'. ".jpg");
        }

        $request->validate([
            'name'          => 'required',
            'description'   => 'required',
            'price'         => 'required',
            'id_category'   => 'required',
            'strok'         => 'required',
            'img1'          => 'required|image|max:2044',
            'img2'          => 'image|max:2024',
            'img3'          => 'image|max:2024',

        ]);



        $product = Product::findOrFail($id);
        $product->fill($request->all());

        //pregunta si la imagen ya exite
        if ($request->hasFile('img1')) {

            //elimina la imagen
            Storage::delete($product->img1);

            $image1 = $request->file('img1')->storeAs('public/img', $product->name. '.jpg');

            if ($request->hasFile('img2')) {

                Storage::delete($product->img2);

                $image2 = $request->file('img2')->storeAs('public/img', $product->name.'2'. '.jpg');

                if ($request->hasFile('img3')) {

                    Storage::delete($product->img3);

                    $image3 = $request->file('img3')->storeAs('public/img', $product->name.'3'. '.jpg');
                }
            }
        }




        $img1 = Storage::url($image1);
        $img2 = Storage::url($image2);
        $img3 = Storage::url($image3);

        $rows = DB::table('products')
                ->where('id', $request->id)
                ->update([
                    'name'          => $request->name,
                    'description'   => $request->description,
                    'price'         => $request->price,
                    'id_category'   => $request->id_category,
                    'stock'         => $request->stock,
                    'img1'          => $img1,
                    'img2'          => $img2,
                    'img3'          => $img3,


                ]);
*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $product = Product::find($id);

        $url = str_replace('storage', 'public', $product->img1);
        $url2 = str_replace('storage', 'public', $product->img2);
        $url3 = str_replace('storage', 'public', $product->img3);
        Storage::delete($url, $url2, $url3);
        $product->delete();

        return redirect()->route('products.index')->with('mensaje', '¡Producto eliminado con exito!');
    }
}
