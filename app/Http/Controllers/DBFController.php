<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use XBase\TableReader;
use App\Traits\ApiResponder;
use Exception;

class DBFController extends Controller
{
  use ApiResponder;
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //show all DBF data

    $table = new TableReader(resource_path('dbf\CHECKS.DBF'));
    $records = [];
    $column_headers = $table->getColumns();

    while ($record = $table->nextRecord()) {
      $record_entry = [];
      foreach ($column_headers as $column) {
        try {
          $record_entry = [$column->getName(), $record->$column];
        } catch (Throwable $e) {
        }
        array_push($records, $record_entry);
      }
    }
    return $this->success(['dbf' => $records], 200);
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
  public function show($id)
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
