<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TreemapController extends Controller
{
    public function index()
    {
        $data = DB::table('dados')->limit(5)->get();
        

        return view('treemap', compact('data'));
    }
}
