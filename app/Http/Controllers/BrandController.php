<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Brand;

class BrandController extends Controller
{
	public function search(Request $request) 
	{
		$keyword = $request->q;
		$brands = Brand::where('name', 'like', '%' . $keyword . '%')->where('active', 1)->orderBy('name', 'asc')->get();

		return response()->json($brands);
	}
}
