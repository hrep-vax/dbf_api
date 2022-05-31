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
use App\Models\Checks_record;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class DBFController extends Controller
{
  use ApiResponder;
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(String $filename)
  {
    //show all DBF data
    //$table = new TableReader(resource_path('dbf\special\ramaneta\JAN22.DBF'));
    //$table = new TableReader(resource_path('dbf\special\rata\jan22.DBF'));
    // $table = new TableReader(resource_path('dbf\special\rata\feb22.DBF'));
    //$table = new TableReader(resource_path('dbf\special\rata\mar22.DBF'));
    $table = new TableReader(storage_path('app\\' . $filename));

    // $table = new TableReader(resource_path("dbf\.'$name'.DBF"));
    //$table = new TableReader(resource_path('dbf\CHECKS.DBF')); // eto yung nagbabago lagi kailangan mag upload ng 
    //user ng update sa dbf, so make sure na makukuha ng system un upload nila
    // $table = new TableReader(resource_path('dbf\PAYEES.DBF'));
    //$table = new TableReader(resource_path('dbf\med50k.DBF'));
    // // trigger batch process : specific time
    // // create log file  to check records count kung nag tally sa dbf, and then unique check no.
    // // return sucess message
    // $path = Storage::disk('public')->path($filename);
    // $resource_path = Storage::disk($foldername)->path($filename); //check if file exist

    //$resource_path = Storage::path($filename);
    // $table = new TableReader(storage_path($resource_path));
    //$table = new TableReader(storage_path('dbf\c_advice\c_advice.DBF'));
    // $filename = 'c_advice.dbf';
    //$table = new TableReader(storage_path('app\\dbf\\c_advice\\c_advice.dbf'));

    $records = [];
    $record_entry = [];
    $column_headers = $table->getColumns();
    while ($record = $table->nextRecord()) {
      foreach ($column_headers as $column) {
        try {


          $record_value = utf8_encode($record->get($column->getName()));

          //comment
          $record_entry[$column->getName()] = $record_value;
        } catch (Throwable $e) {
        }
      }


      array_push($records, $record_entry);
    }

    ///////////////////////////////////////
    //Check::insert($records);
    //Payee::insert($records);
    //Checks_record::insert($records);

    $insert_data = collect($records); // Make a collection to use the chunk method

    // // it will chunk the dataset in smaller collections containing 500 values each. 
    // // Play with the value to get best result
    $chunks = $insert_data->chunk(1000);

    foreach ($chunks as $chunk) {
      //Check::insert($chunk->toArray()); //append to exisitng database
      Checks_record::insert($chunk->toArray());
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
      //resource_path('dbf\CHECKS.DBF'),
      resource_path('dbf\MED50k.DBF'),
      [
        'editMode' => TableEditor::EDIT_MODE_CLONE, //default
      ]
    );
    $record = $table->appendRecord();
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
    $records = [];
    $emp_id = $request['emp_id'];


    $record_entry = [];
    while ($record = $table->nextRecord()) {
      $record_value = $record->get('code');

      if ($emp_id == $record_value) {
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

    //echo ($request);3 

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
  public function upload(Request $request)
  {
    $file = $request->file('file');
    $filename = $file->getClientOriginalName();
    $foldername = $request->foldername;

    //$path = Storage::disk('local')->put($foldername, $file);
    $path = Storage::disk('local')->put($filename, file_get_contents($file));

    // Manually specify a filename...
    //$path = Storage::putFileAs('photos', new File('/path/to/photo'), 'photo.jpg');
    //$path = Storage::putFileAs('dbf', new File('storage/app'), $filename);
    // $path = Storage::putFile($file, new File('local'));
    //$path = Storage::put($filename, $file);

    // $path = $file->put($filename);
    //$path = $file->storeAs('resources/dbf/', $filename); 
    if ($path) {
      self::index($filename); //send folder name and file name to convert
      return response()->json(['message' => 'file uploaded'], 200);
    } else {
      return response()->json(['message' => 'file upload error'], 503);
    }
  }
}
