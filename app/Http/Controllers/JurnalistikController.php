<?php

namespace App\Http\Controllers;

use App\Models\Jurnalistik;
use App\Http\Requests\StorepostRequest;
use App\Http\Requests\UpdatepostRequest;
use App\Models\CategoryJurnalistik;
use App\Models\KomentarJurnalistik;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class JurnalistikController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keterangan = '';
        if(request('categoryJurnalistik')) {
            $categoryJurnalistik = CategoryJurnalistik::firstWhere('slug', request('categoryJurnalistik'));
            $keterangan = ' dengan pilihan kategori ' . $categoryJurnalistik->name;
        }

        return view('jurnalistik', [
            "tittle" => "Jurnalistik",
            "keterangan" => $keterangan,
            "active" => 'jurnalistik',
            "jurnalistik" => Jurnalistik::latest()->filter(request(['search', 'categoryJurnalistik']))->paginate(6)->withQueryString()
        ]);
    }

    public function show(Jurnalistik $jurnalistik) {
        return view('post_jurnalistik', [
            "tittle" => "Single Post",
            "active" => 'jurnalistik',
            "post_jurnalistik" => $jurnalistik
        ]);
        // return Post::find($slug);
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
     * @param  \App\Http\Requests\StorepostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorepostRequest $request)
    {
        //
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\post  $post
     * @return \Illuminate\Http\Response
     */
    // public function show(post $post)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Jurnalistik $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatepostRequest  $request
     * @param  \App\Models\post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatepostRequest $request, Jurnalistik $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jurnalistik $post)
    {
        //
    }

    // ini adalah komentar
    public function komentar (Request $request)
    {
        // dd($request->all());
        // $validatedData['user_id'] = Auth::id();

        $request->request->add(['user_id' =>  auth()->user()->id]);
        $komentarjurnalistik = KomentarJurnalistik::create($request->all());
        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan');
    }
}
