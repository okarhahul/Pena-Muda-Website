<?php

namespace App\Http\Controllers;

use App\Models\CategoryJurnalistik;
use Illuminate\Http\Request;

class AdminControllerJurnalistik extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.kategori.categoryjurnalistik', [
            'categoryJurnalistik' => CategoryJurnalistik::all()
        ]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CategoryJurnalistik  $categoryJurnalistik
     * @return \Illuminate\Http\Response
     */
    public function show(CategoryJurnalistik $categoryJurnalistik)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CategoryJurnalistik  $categoryJurnalistik
     * @return \Illuminate\Http\Response
     */
    public function edit(CategoryJurnalistik $categoryJurnalistik)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategoryJurnalistik  $categoryJurnalistik
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CategoryJurnalistik $categoryJurnalistik)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategoryJurnalistik  $categoryJurnalistik
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoryJurnalistik $categoryJurnalistik)
    {
        //
    }
}
