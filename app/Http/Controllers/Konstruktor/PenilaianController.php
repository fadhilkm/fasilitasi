<?php

namespace App\Http\Controllers\Konstruktor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        $magang = $request->input('magang');
        $aspeks = $request->input('penilaian');
        \App\Konstruktor::where('magang_id',$magang['id'])->where('user_id',auth()->user()->id);

        $request->validate([
            'penilaian.*.sub_aspek_nilai.*.nilai'=> 'required|integer|lte:100'
        ]);
        foreach($aspeks as $aspek){
            foreach($aspek['sub_aspek_nilai'] as $sub_aspek_nilai){
                $penilaian = \App\Penilaian::firstOrNew(['magang_id'=>$magang['id'], 'sub_aspek_nilai_id'=>$sub_aspek_nilai['id']]);
                $penilaian->nilai = $sub_aspek_nilai['nilai'];
                $penilaian->save();
            }
        }
        return $this->getNilai($magang['id']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function getnilai($magang_id){
        $aspek = \App\AspekNilai::all();
        foreach($aspek as $aspek_){
            foreach($aspek_->sub_aspek_nilai as $subaspek_){
                $penilaian = $subaspek_->penilaian()->where('magang_id',$magang_id)->first();
                $subaspek_->nilai = $penilaian!=null ? $penilaian->nilai:0;
            }
        }
        return ['magang'=>\App\Magang::with('users')->findOrFail($magang_id), 'penilaian'=>$aspek];
    }
}
