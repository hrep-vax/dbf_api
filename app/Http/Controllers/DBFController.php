<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use XBase\TableReader;
use XBase\TableEditor;
use App\Traits\ApiResponder;
use Exception;
use App\Models\Checks_record;
use Illuminate\Support\Facades\Storage;

class DBFController extends Controller
{
  use ApiResponder;
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(String $url)
  {

    // $table = new TableReader(storage_path('app\\' . $filename));

    // $contents = Storage::get($url);
    $contents = Storage::disk('s3')->get($url);
    $test_content = Storage::disk('s3')->put($url, $contents);
    $table = new TableReader($test_content);


    $records = [];
    $record_entry = [];
    $column_headers = $table->getColumns();
    while ($record = $table->nextRecord()) {
      foreach ($column_headers as $column) {
        try {

          $record_value = utf8_encode($record->get($column->getName()));

          $record_entry[$column->getName()] = $record_value;
        } catch (Throwable $e) {
        }
      }


      array_push($records, $record_entry);
    }


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

    $final_output =  $records;
    return $this->success(["dbf" => $final_output], 200);
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
    // $foldername = $request->foldername;
    /** Save to S3 */
    $t = Storage::disk('s3')->put($filename, file_get_contents($file), 'public');
    /** get S3 url*/
    $url = Storage::disk('s3')->url($filename);
    //$path = Storage::disk('local')->put($foldername, $file);
    //$path = Storage::disk('local')->put($filename, file_get_contents($file));

    if ($url) {
      self::index($url); //send folder name and file name to convert
      return response()->json(['message' => 'file uploaded'], 200);
    } else {
      return response()->json(['message' => 'file upload error'], 503);
    }
  }
}
