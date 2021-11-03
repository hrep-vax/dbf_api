<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use XBase\TableReader;
use XBase\TableEditor;
use App\Traits\ApiResponder;
use Exception;
use App\Models\Payee2;
use App\Models\Payee;
use App\Models\Check;

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
    //$table = new TableReader(resource_path('dbf\PAYEES.DBF'));
    //$table = new TableReader(resource_path('dbf\med50k.DBF'));
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
      //$payee = Payee2::Create([

      //   'code' => '01783',

      //   'payee1' => 'andy',

      // ]);
      array_push($records, $record_entry);
    }
    //Payee2::insert($records);
    Check::insert($records);
    //Payee::insert($records);

    // $table::chunk(100, function ($checks) {
    //   foreach ($checks as $check) {
    //     Check::insert($check);
    //   }
    // });


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
      //resource_path('dbf\CHECKS.DBF'),
      resource_path('dbf\MED50k.DBF'),
      [
        'editMode' => TableEditor::EDIT_MODE_CLONE, //default
      ]
    );
    $record = $table->appendRecord();
    /*$record->set('voucher', $request->voucher);
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
    $record->set('amount', $request->amount);*/
    $record->set('code', $request->code);
    $record->set('amount', $request->amount);
    $record->set('orno', $request->orno);
    $record->set('duedate', $request->duedate);
    //$record->set('date_used', $request->date_used);
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

    //$table = new TableReader(resource_path('dbf\CHECKS.DBF'));
    $table = new TableReader(resource_path('dbf\MED50k.DBF'));

    // echo ($request);
    $amount = 0;
    $records = [];
    $emp_id = $request['emp_id'];
    $record_entry = [];
    while ($record = $table->nextRecord()) {
      $record_value = $record->get('code');
      if ($emp_id == $record_value) {
        /*$record_entry["voucher"] = $record->get('voucher');
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
        $record_entry["amount"] = $record->get('amount');*/
        $record_entry["code"] = $record->get('code');
        $record_entry["amount"] = $record->get('amount');
        $record_entry["orno"] = $record->get('orno');
        $record_entry["duedate"] = $record->get('duedate');
        $record_entry["date_used"] = $record->get('date_used');
        $record_entry["name"]  = $this->show2($emp_id);
        array_push($records, $record_entry);
      }
    }
    //declare another array
    //name galing sa show2;records
    //array push
    //$name =  $this->show2($emp_id);
    $final_output =  $records;
    return $this->success(["dbf" => $final_output], 200);


    /*$table = new TableReader(resource_path('dbf\CHECKS.DBF'));
    //$table = new TableReader(resource_path('dbf\MEDIC2.DBF'));

    $emp_id = $request->emp_id;a

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
        /*$record_entry["code"] = $record->get('code');
        $record_entry["amount"] = $record->get('amount');
        $record_entry["orno"] = $record->get('orno');
        $record_entry["duedate"] = $record->get('duedate');
        $record_entry["date_used"] = $record->get('date_used');*/
    /*array_push($records, $record_entry);
      }
    }

    return $this->success(['dbf' => $records], 200);*/
  }

  public function show2($id)
  {


    $table = new TableReader(resource_path('dbf\NAME2.DBF'));

    //echo ($request);

    $records2 = [];
    $emp_id2 = $id;
    $record_entry2 = [];
    while ($record2 = $table->nextRecord()) {
      $record_value2 = $record2->get('code');
      if ($emp_id2 == $record_value2) {

        $record_entry2["name"] = $record2->get('name');
        //$record_entry2["code"] = $record2->get('code');

        array_push($records2, $record_entry2);
      }
    }
    //$data = "Sir Andy";
    $data = $records2;
    return $data;
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
      $record->set("amount", $request->amount);


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
