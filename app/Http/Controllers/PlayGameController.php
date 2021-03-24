<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PlayGame;

class PlayGameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('playgame_tictactoe');
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
        $arrRequest = $request->all();
     
        $result = PlayGame::updateOrCreate(
            ["play1_name"=>$arrRequest['formData']['play1'],"play2_name"=>$arrRequest['formData']['play2']],
            [
            "app_name"=>"laravel",
            "play1_name"=>$arrRequest['formData']['play1'],
            "play2_name"=>$arrRequest['formData']['play2'],
            "created_at"=>date("Y-m-d H:i:s"),
            ]);
        
            if($result){
                $arrResponse = ["message"=>"Success","id"=>$result->id];
            }else{
                $arrResponse = ["message"=>"Un Success","id"=>""];
            }

            if(!empty($arrRequest)){
                dd($arrRequest);
            }
          
            return response()->json($arrResponse);
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
}
