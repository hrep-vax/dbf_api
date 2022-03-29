<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Traits\ApiResponder;

use App\Models\Check;

use App\Models\Payee;

use App\Models\Checks_record;

use App\Models\User;

use DB;


class CheckController extends Controller
{
  use ApiResponder;
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
  public function show(Request $request)
  {
    $code = $request['emp_id'];

    $checks = Checks_record::where('checks_records.hrep_id', '=', $code)
      ->get(['checks_records.*']);

    // $checks = Checks_record::join('users', 'checks_records.hrep_id', '=', 'users.hrep_id')
    //   ->where('checks_records.hrep_id', '=', $code)
    //   ->get(['checks_records.*', 'users.*']);

    // $checks = Check::join('payees', 'checks.code', '=', 'payees.code')
    // ->where('checks.code', '=', $code)
    //   ->get(['checks.*', 'payees.payee1']); 



    //$checks = Check::select('payees.*')->join('checks', 'checks.code', '=', 'payees.code');
    //$checks = Check::where('code', '=', $code)
    //->where('payee', function ($query) use ($code) {
    //  $query->where('code', '=', $code);
    //})
    //  ->get();
    //$payee = Payee::where('code', '=', $code)
    //  ->get();

    //$data = $checks->merge($payee);

    // return $this->success(['check' => $checks . $payee], 200);
    return $this->success(['check' => $checks], 200);
    //return $this->success(['check' => $data], 200);
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
