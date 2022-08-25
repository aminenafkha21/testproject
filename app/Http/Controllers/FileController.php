<?php

namespace App\Http\Controllers;

use App\Models\File;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\UploadedFile;


class FileController extends Controller
{


	public function uploadfiles(Request $request) {

		/*
	if(!$request->hasFile('images')) {
		return response()->json(['upload_file_not_found'], 400);
	}
 
	$allowedfileExtension=['pdf','jpg','png'];
	$files = $request->file('images'); 
	$errors = [];
 
				foreach ($request->file('images') as $file) {      
			
					$extension = $file->getClientOriginalExtension();
			
					$check = in_array($extension,$allowedfileExtension);
			
						
							$original_file_name = $file->getClientOriginalName();
							$save = new File();
							$save->name = $original_file_name;
							$save->size = 20;
							$save->save();
			
				}

	return response()->json(['file_uploaded'], 200); */
	$validator = \Validator::make($request->all(), [
		'files' => 'required'
	])->validate();

	$total_files = $request->file('files');
    $images = $request->file('files');

	foreach ($images as $file) {
		// rename & upload files to uploads folder
		
		$name = $file->getClientOriginalName();
		$size=$file->getSize();
	

		// store in db
		$fileUpload = new File();
		$fileUpload->name =  $name;
		$fileUpload->size = $size;
		$fileUpload->save();


	}


	
	

			return response()->json($request->file('files'), 200);
	}

	 // get all files 
	public function index () {

		$files = File::orderBy('created_at','DESC')->paginate();
		if(!$files->isEmpty()){
            return response()->json(File::all(),200);

		}else{
			$data = array(
				'message' => 'No Files found!'
			);
			$status = 400;
		}
		return response()->json($data,$status);
    }

		 // get all archived files 
		 public function archivedfiles () {

			$files = File::where('archivedAt','!=',NULL)->get();
			if(count($files)>0){
				return response()->json($files,200);
	
			}else{
				$data = array(
					'message' => 'No Files found!'
				);
				$status = 400;
			}
			return response()->json($data,$status);
		}


				 // get all favourites files 
				 public function favourites () {

					$files = File::where('starredAt','!=',NULL)->get();
					if(count($files)>0){
						return response()->json($files,200);
			
					}else{
						$data = array(
							'message' => 'No Files found!'
						);
						$status = 400;
					}
					return response()->json($data,$status);
				}


	// Archived files
	public function archiveFiles(Request $request)
	{  $validator = Validator::make($request->all(), [
		'id'=> 'required',
	  ]);        
	  //Check the validation becomes fails or not
	  if ($validator->fails()) {
		//Return error message
		return response()->json(['error'=> $validator->errors()->all()], 400);
	  }  
	  if(File::where('id', $request->id)->exists())
	  {   
		 try {
			 $id = $request->id;
			 $files = File::findOrFail($id);
			 $files->archivedAt = Carbon::now();
			 $files->save();
			 return response()->json(
			 $files, 200);
			} catch (ModelNotFoundException $e) {
				$message= 'No files Found ';
			 return response()->json(
			  $message
			 , 404);
			}  
	  }
	  else{
		  $message= 'ID FILE IS WRONG';
		return response()->json(
			$message
		, 404);
	 }  
	}




		// UnArchived files
		public function unarchiveFiles(Request $request)
		{  $validator = Validator::make($request->all(), [
			'id'=> 'required',
		  ]);        
		  //Check the validation becomes fails or not
		  if ($validator->fails()) {
			//Return error message
			return response()->json(['error'=> $validator->errors()->all()], 400);
		  }  
		  if(File::where('id', $request->id)->exists())
		  {   
			 try {
				 $id = $request->id;
				 $files = File::findOrFail($id);
				 $files->archivedAt = null;
				 $files->save();
				 return response()->json(
				 $files, 200);
				} catch (ModelNotFoundException $e) {
					$message= 'No files Found ';
				 return response()->json(
				  $message
				 , 404);
				}  
		  }
		  else{
			  $message= 'ID FILE IS WRONG';
			return response()->json(
				$message
			, 404);
		 }  
		}











		// make file to Favourites files
	public function favouriteFiles(Request $request)
	{  $validator = Validator::make($request->all(), [
		'id'=> 'required',
	  ]);        
	  //Check the validation becomes fails or not
	  if ($validator->fails()) {
		//Return error message
		return response()->json(['error'=> $validator->errors()->all()], 400);
	  }  
	  if(File::where('id', $request->id)->exists())
	  {   
		 try {
			 $id = $request->id;
			 $files = File::findOrFail($id);
			 $files->starredAt = Carbon::now();
			 $files->save();
			 return response()->json(
			 $files, 200);
			} catch (ModelNotFoundException $e) {
				$message= 'No files Found ';
			 return response()->json(
			  $message
			 , 404);
			}  
	  }
	  else{
		  $message= 'ID FILE IS WRONG';
		return response()->json(
			$message
		, 404);
	 }  
	}




		// UnFavourites files
		public function removefavouriteFiles(Request $request)
		{  $validator = Validator::make($request->all(), [
			'id'=> 'required',
		  ]);        
		  //Check the validation becomes fails or not
		  if ($validator->fails()) {
			//Return error message
			return response()->json(['error'=> $validator->errors()->all()], 400);
		  }  
		  if(File::where('id', $request->id)->exists())
		  {   
			 try {
				 $id = $request->id;
				 $files = File::findOrFail($id);
				 $files->starredAt =null ;
				 $files->save();
				 return response()->json(
				 $files, 200);
				} catch (ModelNotFoundException $e) {
					$message= 'No files Found ';
				 return response()->json(
				  $message
				 , 404);
				}  
		  }
		  else{
			  $message= 'ID FILE IS WRONG';
			return response()->json(
				$message
			, 404);
		 }  
		}
}
