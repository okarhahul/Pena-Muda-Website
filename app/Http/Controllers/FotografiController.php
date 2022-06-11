<?php

namespace App\Http\Controllers;

use App\Models\Fotografi;
use App\Http\Requests\StoreFotografiRequest;
use App\Http\Requests\UpdateFotografiRequest;

class FotografiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('fotografi', [
            "tittle" => "Fotografi",
            "active" => 'fotografi',
            // "posts" => Jurnalistik::all()
            "fotografi" => Fotografi::latest()->filter(request(['search']))->paginate(6)->withQueryString()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fotografi  $fotografi
     * @return \Illuminate\Http\Response
     */
    public function show(Fotografi $fotografi)
    {
        return view('post_fotografi', [
            "tittle" => "Single Post",
            "active" => 'fotografi',
            "post_fotografi" => $fotografi
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
     * @param  \App\Http\Requests\StoreFotografiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFotografiRequest $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fotografi  $fotografi
     * @return \Illuminate\Http\Response
     */
    public function edit(Fotografi $fotografi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFotografiRequest  $request
     * @param  \App\Models\Fotografi  $fotografi
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFotografiRequest $request, Fotografi $fotografi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fotografi  $fotografi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fotografi $fotografi)
    {
        //
    }
}
