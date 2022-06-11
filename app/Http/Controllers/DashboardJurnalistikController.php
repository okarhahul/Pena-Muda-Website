<?php

namespace App\Http\Controllers;

use App\Models\Jurnalistik;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CategoryJurnalistik;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardJurnalistikController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard/divisi/jurnalistik', [
            'jurnalistik' => Jurnalistik::where('user_id', auth()->user()->id)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.create.createjurnalistik', [
            'categoryJurnalistik' => CategoryJurnalistik::all()
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
            'category_jurnalistik_id' => ['required'],
            'image' => ['image', 'file'],
            // 'image' => 'mimes:jpg, png',
            'body' => ['required']
        ]);

        if($request->file('image')){
            $validatedData['image'] = $request->file('image')->store('jurnalistik-images');
        }

        $validatedData['user_id'] = Auth::id();
        
        // dd($validateData);

         // Cek ketika ada judul yg sama, maka slug nya kita bedain
 
        // ambil semua data Jurnalistik
        $jurnalistik = Jurnalistik::all();
 
        // cocokin apakah udah ada
        $filteredJudul = $jurnalistik->where('judul', $validatedData['judul'])->all();
 
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

        Jurnalistik::create($validatedData);

        return redirect('/dashboard/jurnalistik');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Jurnalistik  $jurnalistik
     * @return \Illuminate\Http\Response
     */
    public function show(Jurnalistik $jurnalistik)
    {
        return view('dashboard.show.showjurnalsitik', [
            'jurnalistik' => $jurnalistik
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Jurnalistik  $jurnalistik
     * @return \Illuminate\Http\Response
     */
    public function edit(Jurnalistik $jurnalistik)
    {
        return view('dashboard.edit.editjurnalistik', [
            'jurnalistik' => $jurnalistik,
            'categoryJurnalistik' => CategoryJurnalistik::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Jurnalistik  $jurnalistik
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jurnalistik $jurnalistik)
    {
        /*
        casenya adalah ketika menggupdate data dengan mengganti judul, maka slug akan keganti
        */

        // ini kalo title nya ganti ,sama kita pakein slug yg udah ada di db sblumnya
        if($jurnalistik->judul == $request->judul) {
 
            $currentSlug = $jurnalistik->slug;
        } else {
 
            // cek kalo judul ganti, pastiin di db udah ada blom
            $queryJudul = Jurnalistik::where('judul', $request->judul)->get();
 
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
            'category_jurnalistik_id' => ['required'],
            // 'image' => 'mimes:jpg, png',
            'image' => ['image', 'file'],
            'body' => ['required']
        ]);

        // validasi apakah ada gambar baru atau tidak
        if($request->file('image')){

            if($request->oldImage){
                Storage::delete($request->oldImage);
            }
            $validatedData['image'] = $request->file('image')->store('jurnalistik-images');
        }
 
        // add anoteher field
        $validatedData['slug'] = $currentSlug;
        $validatedData['user_id'] = Auth::id(); // ambil id yg sedang login
        $validatedData['headline'] = Str::limit(strip_tags($validatedData['body'], 25));
 
        Jurnalistik::where('id', $jurnalistik->id)
            ->update($validatedData);
 
        return redirect('/dashboard/jurnalistik')->with('success', "Postingan berhasil di ubah");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Jurnalistik  $jurnalistik
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jurnalistik $jurnalistik)
    {

        if($jurnalistik->image){
            Storage::delete($jurnalistik->image);
        }

        Jurnalistik::destroy($jurnalistik->id);
        return redirect('/dashboard/jurnalistik')->with('success', 'Postingan berhasil dihapus!');
    }
}
