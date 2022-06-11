<?php

namespace App\Http\Controllers;

use App\Models\Fotografi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardFotografiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard/divisi/fotografi', [
            'fotografi' => Fotografi::where('user_id', auth()->user()->id)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.create.createfotografi');
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
            'image' => ['image', 'file'],
            // 'image' => 'mimes:jpg, png',
            'body' => ['required']
        ]);

        if($request->file('image')){
            $validatedData['image'] = $request->file('image')->store('fotografi-images');
        }

        $validatedData['user_id'] = Auth::id();
        
        // dd($validateData);

         // Cek ketika ada judul yg sama, maka slug nya kita bedain
 
        // ambil semua data Jurnalistik
        $fotografi = Fotografi::all();
 
        // cocokin apakah udah ada
        $filteredJudul = $fotografi->where('judul', $validatedData['judul'])->all();
 
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

        Fotografi::create($validatedData);

        return redirect('/dashboard/fotografi');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fotografi  $fotografi
     * @return \Illuminate\Http\Response
     */
    public function show(Fotografi $fotografi)
    {
        return view('dashboard.show.showfotografi', [
            'fotografi' => $fotografi
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fotografi  $fotografi
     * @return \Illuminate\Http\Response
     */
    public function edit(Fotografi $fotografi)
    {
        return view('dashboard.edit.editfotografi', [
            'fotografi' => $fotografi,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fotografi  $fotografi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fotografi $fotografi)
    {
       /*
        casenya adalah ketika menggupdate data dengan mengganti judul, maka slug akan keganti
        */

        // ini kalo title nya ganti ,sama kita pakein slug yg udah ada di db sblumnya
        if($fotografi->judul == $request->judul) {
 
            $currentSlug = $fotografi->slug;
        } else {
 
            // cek kalo judul ganti, pastiin di db udah ada blom
            $queryJudul = Fotografi::where('judul', $request->judul)->get();
 
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
            'image' => ['image', 'file'],
            // 'image' => 'mimes:jpg, png',
            'body' => ['required']
        ]);

        // validasi apakah ada gambar baru atau tidak
        if($request->file('image')){

            if($request->oldImage){
                Storage::delete($request->oldImage);
            }
            $validatedData['image'] = $request->file('image')->store('fotografi-images');
        }
 
        // add anoteher field
        $validatedData['slug'] = $currentSlug;
        $validatedData['user_id'] = Auth::id(); // ambil id yg sedang login
        $validatedData['headline'] = Str::limit(strip_tags($validatedData['body'], 25));
 
        Fotografi::where('id', $fotografi->id)
            ->update($validatedData);
 
        return redirect('/dashboard/fotogra$fotografi')->with('success', "Postingan berhasil di ubah");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fotografi  $fotografi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fotografi $fotografi)
    {
        if($fotografi->image){
            Storage::delete($fotografi->image);
        }

        Fotografi::destroy($fotografi->id);
        return redirect('/dashboard/fotografi')->with('success', 'Postingan berhasil dihapus!');
    }
}
