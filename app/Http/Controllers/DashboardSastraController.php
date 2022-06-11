<?php

namespace App\Http\Controllers;

use App\Models\Sastra;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\CategorySastra;
use Illuminate\Support\Facades\Storage;

class DashboardSastraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard/divisi/sastra', [
            'sastra' => Sastra::where('user_id', auth()->user()->id)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.create.createsastra', [
            'categorySastra' => CategorySastra::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'judul' => ['required', 'max:255'],
            'category_sastra_id' => ['required'],
            'image' => ['image', 'file'],
            'body' => ['required']
        ]);

        if($request->file('image')){
            $validatedData['image'] = $request->file('image')->store('sastra-images');
        }

        $validatedData['user_id'] = Auth::id();
        
        // dd($validateData);

         // Cek ketika ada judul yg sama, maka slug nya kita bedain
 
        // ambil semua data Jurnalistik
        $sastra = Sastra::all();
 
        // cocokin apakah udah ada
        $filteredJudul = $sastra->where('judul', $validatedData['judul'])->all();
 
        // kalo ada, slug nya di bedain
        // kalo ga ya yauda paek yg request judul yg baru
        if($filteredJudul != null) {
            $judul = collect($filteredJudul)->first()->judul;
            // $newSlug = Str::of($judul)->append(" " . Str::random(3));
            $newSlug = Str::of($judul)->append(" " . mt_rand(1,10));
            $validatedData['slug'] = Str::slug($newSlug, '-');
        } else {
            $validatedData['slug'] = Str::slug($validatedData['judul'], '-');
        }
 
        $validatedData['headline'] = Str::limit(strip_tags($validatedData['body'], 25));

        // dd($validatedData);

        Sastra::create($validatedData);

        return redirect('/dashboard/sastra');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sastra  $sastra
     * @return \Illuminate\Http\Response
     */


    public function show(Sastra $sastra)
    {
        return view('dashboard.show.showsastra', [
            'sastra' => $sastra
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sastra  $sastra
     * @return \Illuminate\Http\Response
     */
    public function edit(Sastra $sastra)
    {
        return view('dashboard.edit.editsastra', [
            'sastra' => $sastra,
            'categorySastra' => CategorySastra::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sastra  $sastra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sastra $sastra)
    {
       /*
        casenya adalah ketika menggupdate data dengan mengganti judul, maka slug akan keganti
        */

        // ini kalo title nya ganti ,sama kita pakein slug yg udah ada di db sblumnya
        if($sastra->judul == $request->judul) {
 
            $currentSlug = $sastra->slug;
        } else {
 
            // cek kalo judul ganti, pastiin di db udah ada blom
            $queryJudul = Sastra::where('judul', $request->judul)->get();
 
            // ada judul yg sama di db
            if(count($queryJudul) != 0) {
                $currentSlug = Str::slug($request->judul . " " . Str::random(5), '-');
            } else {
 
                // ga sama dgn di db
                $currentSlug =  Str::slug($request->judul, '-');
            }
 
        }
 
        // validasi
        $validatedData = $request->validate([
            'judul' => ['required', 'max:255'],
            'category_sastra_id' => ['required'],
            'image' => ['image', 'file'],
            'body' => ['required']
        ]);

        // validasi apakah ada gambar baru atau tidak
        if($request->file('image')){

            if($request->oldImage){
                Storage::delete($request->oldImage);
            }
            $validatedData['image'] = $request->file('image')->store('sastra-images');
        }
 
        // add anoteher field
        $validatedData['slug'] = $currentSlug;
        $validatedData['user_id'] = Auth::id(); // ambil id yg sedang login
        $validatedData['headline'] = Str::limit(strip_tags($validatedData['body'], 25));
 
        Sastra::where('id', $sastra->id)
            ->update($validatedData);
 
        return redirect('/dashboard/sastra')->with('success', "Postingan berhasil di ubah");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sastra  $sastra
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sastra $sastra)
    {
        if($sastra->image){
            Storage::delete($sastra->image);
        }

        Sastra::destroy($sastra->id);
        return redirect('/dashboard/sastra')->with('success', 'Postingan berhasil dihapus!');
    }
}
