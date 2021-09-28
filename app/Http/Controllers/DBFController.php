<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use XBase\TableReader;
use XBase\TableEditor;
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
    $record_entry = [];
    $column_headers = $table->getColumns();
    while ($record = $table->nextRecord()) {
      foreach ($column_headers as $column) {
        try {
          $record_value = $record->get($column->getName());
          $record_entry[$column->getName()] = $record_value;
        } catch (Throwable $e) {
        }
      }
      array_push($records, $record_entry);
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
    // add record
    $table = new TableEditor(
      resource_path('dbf\CHECKS.DBF'),
      [
        'editMode' => TableEditor::EDIT_MODE_CLONE, //default
      ]
    );
    $record = $table->appendRecord();
    $record->set('voucher', $request->voucher);
    $record->set('check', $request->check);
    $record->set('code', $request->code);
    $record->set('oblig', $request->oblig);
    $record->set('obj_clas', $request->obj_clas);
    $record->set('che_date', $request->che_date);
    $record->set('date_sign', $request->date_sign);
    $record->set('rec_date', $request->rec_date);
    $record->set('rec_opis', $request->rec_opis);
    $record->set('ret_date', $request->ret_date);
    $record->set('ret_time', $request->ret_time);
    $record->set('rel_date', $request->rel_date);
    $record->set('rel_name', $request->rel_name);
    $record->set('or_number', $request->or_number);
    $record->set('amount', $request->amount);
    $table->writeRecord();
    $table->save();


    return $this->success(['message' => 'Record Added Successfully'], 200);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request)
  {
    $table = new TableReader(resource_path('dbf\CHECKS.DBF'));

    $emp_id = $request->emp_id;

    $records = [];
    $record_entry = [];
    while ($record = $table->nextRecord()) {
      $record_value = $record->get('code');
      if ($emp_id == $record_value) {
        $record_entry["voucher"] = $record->get('voucher');
        $record_entry["check"] = $record->get('check');
        $record_entry["code"] = $record->get('code');
        $record_entry["oblig"] = $record->get('oblig');
        $record_entry["obj_clas"] = $record->get('oblig');
        $record_entry["che_date"] = $record->get('che_date');
        $record_entry["date_sign"] = $record->get('date_sign');
        $record_entry["rec_date"] = $record->get('rec_date');
        $record_entry["rec_opis"] = $record->get('rec_opis');
        $record_entry["ret_date"] = $record->get('ret_date');
        $record_entry["ret_time"] = $record->get('ret_time');
        $record_entry["rel_date"] = $record->get('rel_date');
        $record_entry["rel_name"] = $record->get('rel_name');
        $record_entry["or_number"] = $record->get('or_number');
        $record_entry["amount"] = $record->get('amount');
        array_push($records, $record_entry);
      }
    }

    return $this->success(['dbf' => $records], 200);
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
    // update record

    $table = new TableEditor(resource_path('dbf\CHECKS.DBF'));

    for ($i = 0; $i < 10; $i++) {
      $record = $table->nextRecord();
      $record->set("voucher", $request->voucher);


      $table->writeRecord();
    }
    return $this->success(['message' => 'Record Updated Successfully'], 200);
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
