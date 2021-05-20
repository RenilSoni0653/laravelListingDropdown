<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class CountryCityStateController extends Controller
{
    public function index(Request $request)
    {
        $user = Product::paginate(10);
        $search = $request->input('search');
        
        $users = Product::query()
        ->where('country','LIKE',$search)
        ->orWhere('city','LIKE',$search)
        ->orWhere('state','LIKE',$search)
        ->orWhere('name','LIKE',$search)->get();

        return view('search',compact('users'));
    }
}
