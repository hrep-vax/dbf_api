<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Check;

use DB;

class CheckController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $checks = Check::all();

    return $this->success(['checks' => $checks], 200);
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
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  //public function show($id)
  //{
  //
  //}

  //public function show(Check $code)
  //{
  //  return $this->success(["check" => $Check], 200);
  //  //return view('post', [

  //  // 'post' => $post

  //  // //'comments' => Comment::all()
  //  //]);
  // }

  public function show(Request $request)
  {
    $code = $request['code'];
    //$checks = Check::find($code);
    $checks = Check::all();

    return $this->success(['checks' => $checks], 200);
  }
  //public function show(Request $request)
  //{
  //  $code = $request['code'];
  //  $checks = Check::search($code)->get;
  //  return $this->success(['resource' => $checks], 200);
  //}

  //public function show(Request $request)
  //{
  //  $code = $request['code'];
  //  $checks = DB::table('checks')
  //    ->where('code', '=', $code)
  //    ->get();
  //  ////$checks = DB::select('select * from checks');
  //  return $this->success(['checks' => $checks], 200);
  //}

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
