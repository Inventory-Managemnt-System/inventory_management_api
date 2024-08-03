<?php

namespace App\Http\Controllers;

use App\Models\AllSchools;
use App\Models\Item;
use App\Models\NewItem;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    //
    public function index(): JsonResponse
    {
        // $school1 = AllSchools::where('SCHOOL_NAME', 'Acc Irrua School')->first();
        // $iruwa = NewItem::where('school', 'Acc Irrua School')->get();
        // $school1->newItems()->attach($iruwa);
        // $items = Item::latest()->get();
        
      $items = NewItem::all();


        return response()->json(["items" => $items], Response::HTTP_OK);
    }

    public function uploadItemsBulk(Request $request){
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        $file = $request->file('file');
        $filePath = $file->getRealPath();
        $fileHandle = fopen($filePath, 'r');
    
        $header = fgetcsv($fileHandle, 0, ',');
    
        while (($row = fgetcsv($fileHandle, 0, ',')) !== FALSE) {
            $data = array_combine($header, $row);
    
            // Insert user data into the database
            NewItem::create([
                'item_code' => $data['item_code'],
                'additional_info' => $data['additional_info'],
                'item_name' => bcrypt($data['item_name']),
                'subject_category'=>$data['subject_category'],
                'distribution'=>$data['distribution'],
                'quantity'=>$data['quantity'],
                'school'=>$data['school'],
                
    
            ]);
        }
    
        fclose($fileHandle);
    
        return response()->json(['success' => 'File uploaded and data inserted successfully']);
    }

    public function show(int $id): JsonResponse
    {
        $item = Item::where(["id" => $id])->first();
        if (!$item) return response()->json(["message" => "Item not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        return response()->json(["item" => $item], Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        $request = $this->validate($request, [
            "barcode_id" => "required|string",
            "item_name" => "required|string",
            
            "item_code" =>'required|string',
            
            "category" => "required|string",
            "school" => "required|string",
            "image"=>'required|string',
            
            
            "quantity" => "required|numeric",
            
            "distribution" => "required|string",
        ]);

        // create item
       
      
        $item = new NewItem();
        // $item->unique_id = $this->UniqueID();
        $item->barcode_id = $request['barcode_id'];
        $item->item_name = $request["item_name"];
        $item->image = $request["image"];
        // $item->brand = $request["brand"];
        $item->subject_category = $request["category"];
        $item->school = $request["school"];
        $item->additional_info = 'JSS3';
        $item->item_code = $request["item_code"];
        $item->quantity = $request["quantity"];
        // $item->reorder_point = $request["reorder_point"];
        $item->distribution = $request["distribution"];
        $item->save();
       

        return response()->json(["item" => $item], Response::HTTP_CREATED);
    }

    public function update(Request $request, int $id)
    {
        $request = $this->validate($request, [
           "barcode_id" => "required|string",
            "item_name" => "required|string",
            "additional_info" => "required|string",
            "item_code" =>'required|string',
            
            "category" => "required|string",
            "school" => "required|string",
            
            
            "quantity" => "required|numeric",
            
            "distribution" => "required|string",
        ]);

        // find item
        $item = Item::where(['id' => $id])->first();
        if(!$item) return response()->json(["message" => "Item not found"], Response::HTTP_UNPROCESSABLE_ENTITY);

        $item->update($request);
        return response()->json(["item" => $item], Response::HTTP_OK);
    }

    public function delete()
    {

    }

    protected function UniqueID(int $length=10):string
    {
        $pool = '0123456789';
        $nonZeroPool = '123456789';

        // Generate the first character from non-zero pool
        $firstChar = $nonZeroPool[random_int(0, strlen($nonZeroPool) - 1)];

        // Generate the remaining characters from the full pool
        $remainingChars = '';
        for ($i = 0; $i < $length - 1; $i++) {
            $remainingChars .= $pool[random_int(0, strlen($pool) - 1)];
        }

        // Combine the first character with the remaining characters
        return $firstChar . $remainingChars;
    }

    public function search(string $id): JsonResponse
    {
        $item = Item::where(["id" => $id])->first();
        if (!$item) return response()->json(["message" => "Item not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        return response()->json(["item" => $item], Response::HTTP_OK);
    }

    public function scan(Request $request): JsonResponse
    {
        $request = $this->validate($request, [
            "item_name" => "required|string",
        ]);

        $item = NewItem::where(['item_name' => $request['item_name']])->first();
        if(!$item) return response()->json(["message" => "Item not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        return response()->json(["item" => $item], Response::HTTP_OK);
    }

    public function inventory_report(Request $request)
    {
        if($request->get('lga') == 'AKOKO EDO'){
            
            $edoItems = Item::where('name', 'Pencil')->orWhere('name', 'Eraser')->orWhere('name', 'Sharpner')->get();
            return response($edoItems, 200);
        }
        else if($request->get('lga') =='EGOR'){
            $egorItems = Item::where('name', 'Mathematics Textbook - Grade 2')->orWhere('name', 'Mathematics Textbook – Grade 1')->get();
            return response($egorItems, 200);
        }
        else if($request->get('lga') =='ESAN CENTRAL'){
            $esanItems = Item::where('name', 'ChalkBoard')->orWhere('name', 'Laptops')->get();
            return response($esanItems, 200);
        }
        else if($request->get('schoolType')  =='JSS'){
            $edoItems = Item::where('name', 'Pencil')->orWhere('name', 'Eraser')->orWhere('name', 'Sharpner')->get();
            return response($edoItems, 200);
        }
        else if($request->get('schoolType')  =='Primary'){
            $egorItems = Item::where('name', 'Mathematics Textbook - Grade 2')->orWhere('name', 'Mathematics Textbook – Grade 1')->get();
            return response($egorItems, 200);
        }
        else if($request->get('schoolType')  =='Progressive'){
            $esanItems = Item::where('name', 'ChalkBoard')->orWhere('name', 'Laptops')->get();
            return response($esanItems, 200);
        }
        else{
            $items = Item::all();
            return response($items, 200);
        }
      
        
        // if($request->get('format') == 'pdf') {
            
        //     $pdfContent = ReportService::GeneratePDF($items);
        //     return response()->streamDownload(
        //         fn () => print($pdfContent),
        //         'report'
        //     );
        // } 
      
    }

  

}
