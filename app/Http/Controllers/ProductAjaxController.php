<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use DataTables;

class ProductAjaxController extends Controller
{
    
    public function index(Request $r)
    {
        $data = Product::latest()->get();
        if($r->ajax()){
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" 
                    data-original-tittle="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>';

                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip" data-tip="'.$row->id.'" 
                    data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('productAjax', compact('data'));
    }    

    public function create()
    {
        //
    }

    public function store(Request $r)
    {
        Product::updateOrCreate(['id' => $r->products_id],
            ['name' => $r->name, 'detail' => $r->detail]);

        return response()->json(['success' => 'Product saved successfully.']);
    }

    public function edit($id)
    {
        $product = Product::find($id);
        return response()->json($product);
    }

    public function destroy($id)
    {
        Product::find($id)->delete();

        return response()->json(['success' => 'Product deleted successfully.']);
    }
}
