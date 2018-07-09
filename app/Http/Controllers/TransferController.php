<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('transfer');
    }
}
