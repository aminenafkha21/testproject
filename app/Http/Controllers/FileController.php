<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FileController extends Controller
{



	 // Upload Files

	public function uploadfiles(Request $request) {


	$validator = \Validator::make($request->all(), [
		'files' => 'required'
	])->validate();

	$total_files = $request->file('files');
    $images = $request->file('files');

	foreach ($images as $file) {
		// rename & upload files to uploads folder
		
		$ext = $file->getClientOriginalExtension();
		$name = $file->getClientOriginalName();
		$size=$file->getSize();
		if($ext == 'txt' || $ext == 'pdf'  || $ext == 'doc' || $ext == 'docx' || $ext == 'html' || $ext == 'ppt' || $ext == 'pptx' ) {



			$extension = pathinfo($name, PATHINFO_EXTENSION);
			$sum = File::where('name' , 'LIKE' , "%pdf%" )->orWhere('name' , 'LIKE' , "%txt%" )->orWhere('name' , 'LIKE' , "%doc%" )->orWhere('name' , 'LIKE' , "%docx%" )->orWhere('name' , 'LIKE' , "%html%" )
			->orWhere('name' , 'LIKE' , "%ppt%" )
			->orWhere('name' , 'LIKE' , "%pptx%" )
			->orWhere('name' , 'LIKE' , "%xls%" )
			->get()->sum("size");
			
			$sum=$sum+$size ; 
			
			if($sum > 5000000) {


				return response()->json(' The storage of documents is full!',404);

			}
			


		}


		if($ext == 'jpg' || $ext == 'png'  || $ext == 'gif' || $ext == 'jpeg' || $ext == 'psd' || $ext == 'tiff' || $ext == 'raw' ) {



			$extension = pathinfo($name, PATHINFO_EXTENSION);
			$sum2 = File::where('name' , 'LIKE' , "%jpg%" )->orWhere('name' , 'LIKE' , "%png%" )->orWhere('name' , 'LIKE' , "%gif%" )->orWhere('name' , 'LIKE' , "%jpeg%" )->orWhere('name' , 'LIKE' , "%tiff%" )
			->orWhere('name' , 'LIKE' , "%raw%" )
			->get()->sum("size");
			
			$sum2=$sum2+$size ; 
			

			if($sum2 > 52428800) {


				return response()->json(' The storage of images is full!',404);

			}
			


		}



		if($ext == 'avi' || $ext == 'mp4'   || $ext == 'ts'  || $ext == 'ogg' || $ext == 'swf' || $ext == 'mpv' || $ext == 'mov' || $ext == 'flv'  || $ext == 'wmv' ) {



			$extension = pathinfo($name, PATHINFO_EXTENSION);
			$sum3 = File::where('name' , 'LIKE' , "%mp4%" )->orWhere('name' , 'LIKE' , "%avi%" )->orWhere('name' , 'LIKE' , "%mov%" )->orWhere('name' , 'LIKE' , "%flv%" )->orWhere('name' , 'LIKE' , "%wmv%" )
			->orWhere('name' , 'LIKE' , "%ogg%" )
			->orWhere('name' , 'LIKE' , "%swf%" )
			->orWhere('name' , 'LIKE' , "%mpv%" )
			->orWhere('name' , 'LIKE' , "%ts%" )

			->get()->sum("size");
			
			$sum3=$sum3+$size ; 
			

			if($sum3 > 5368709120) {


				return response()->json(' The storage of videos is full!',404);

			}
			


		}

		
	

		// store in db
		$fileUpload = new File();
		$fileUpload->name =  $name;
		$fileUpload->size = $size;
		$fileUpload->save();


	}


	
	

			return response()->json($request->file('files'), 200);
	}



			// ************************************ All Storage Functions **********************************************//

	 public function storagevideo() {


		$sum3 = File::where('name' , 'LIKE' , "%mp4%" )->orWhere('name' , 'LIKE' , "%avi%" )->orWhere('name' , 'LIKE' , "%mov%" )->orWhere('name' , 'LIKE' , "%flv%" )->orWhere('name' , 'LIKE' , "%wmv%" )
		->orWhere('name' , 'LIKE' , "%ogg%" )
		->orWhere('name' , 'LIKE' , "%swf%" )
		->orWhere('name' , 'LIKE' , "%mpv%" )
		->get()->sum("size");
		

				
		$str = $this->format_size($sum3); 
		$url = explode('+', $str);
		$pour= intval(end($url));

	




		$value=0;
		$containes=Str::contains($str , 'KB' ) ;
		if($containes == true ) {
			$value=(floatval(substr($str, 0, strpos($str,'+')-1)) / 1000)  / 1000;
		}


		$containes2=Str::contains($str , 'MB' ) ;

		if($containes2 == true ) {
			$value=floatval(substr($str, 0, strpos($str,'+')-1)) / 1000 ;
		}else {
			$value=floatval(substr($str, 0, strpos($str,'+')-1)) ;

		}

		$data = array(
			'size' => substr($str, 0, strpos($str,'+')-1),
			'pourcentage' => round($value * 100 / 5 , 2) 

		);

	     return response()->json($data,200); 


	 }

	 public function storageimages() {


		$sum2 = File::where('name' , 'LIKE' , "%jpg%" )->orWhere('name' , 'LIKE' , "%png%" )->orWhere('name' , 'LIKE' , "%gif%" )->orWhere('name' , 'LIKE' , "%jpeg%" )->orWhere('name' , 'LIKE' , "%tiff%" )
		->orWhere('name' , 'LIKE' , "%raw%" )
		->get()->sum("size");
		
		
		
		$str = $this->format_size($sum2); 
		$url = explode('+', $str);
		$pour= intval(end($url));


		$value=0;
		$containes=Str::contains($str , 'KB' ) ;
		if($containes == true ) {
			$value=floatval(substr($str, 0, strpos($str,'+')-1)) / 1000 ;
		}else {
			$value=floatval(substr($str, 0, strpos($str,'+')-1)) ;

		}

		$data = array(
			'size' => substr($str, 0, strpos($str,'+')-1),
			'pourcentage' => round($value * 100 / 50 , 2 )

		);
		$status = 200;

	     return response()->json($data,$status); 


	 }

	 public function storagedoc() {


		$sum = File::where('name' , 'LIKE' , "%pdf%" )->orWhere('name' , 'LIKE' , "%txt%" )->orWhere('name' , 'LIKE' , "%doc%" )->orWhere('name' , 'LIKE' , "%docx%" )->orWhere('name' , 'LIKE' , "%html%" )
			->orWhere('name' , 'LIKE' , "%ppt%" )
			->orWhere('name' , 'LIKE' , "%pptx%" )
			->orWhere('name' , 'LIKE' , "%xls%" )
			->get()->sum("size");
		

				
			$str = $this->format_size($sum); 
			$url = explode('+', $str);
			$pour= intval(end($url));
			$value=0;
			
				$containes=Str::contains($str , 'KB' ) ;
				if($containes == true ) {
					$value=floatval(substr($str, 0, strpos($str,'+')-1)) / 1000 ;
				}else {
					$value=floatval(substr($str, 0, strpos($str,'+')-1)) ;

				}


				$data = array(
					'size' => substr($str, 0, strpos($str,'+')-1),
					'pourcentage' => round($value * 100 / 5 ,  2)

				);

			return response()->json($data,200); 


	 }



	 		// ************************************ End Storage Functions **********************************************//



	 // Conversion Byte to KB MB GB TB
	 function format_size($size) {
		if ($size < 1024) {
		  return $size . ' B';
		}
		else {
		  $size = $size / 1024;
		  $units = ['KB', 'MB', 'GB', 'TB'];
		  foreach ($units as $unit) {
			if (round($size, 2) >= 1024) {
			  $size = $size / 1024;
			}
			else {
			  break;
			}
		  }
		  return round($size, 2) . ' ' . $unit  . ' +' . round(round($size, 2)*100/50 , 0 )  ;
		}
	  }	

	  
	


	  


	 // get all files 
	public function index () {

		$files = File::orderBy('created_at','DESC')->get();
		if(!$files->isEmpty()){
			return FileResource::collection( $files);

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

			$files = File::orderBy('archivedAt','DESC')->where('archivedAt','!=',NULL)->get();
			if(count($files)>0){
				return FileResource::collection( $files);
	
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

					$files = File::orderBy('starredAt','DESC')->where('starredAt','!=',NULL)->get();
					if(count($files)>0){
						return FileResource::collection( $files);
			
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



		// ************************************ All Sorting Functions **********************************************//
		public function sortbydate () { 



			$files = File::orderBy('created_at','ASC')->get();
			if(!$files->isEmpty()){
				return FileResource::collection( $files);
	
			}else{
				$data = array(
					'message' => 'No Files found!'
				);
				$status = 400;
			}
			return response()->json($data,$status);

		}

		public function sortbyname () { 



			$files = File::orderBy('name','DESC')->get();
			if(!$files->isEmpty()){
				return FileResource::collection( $files);
	
			}else{
				$data = array(
					'message' => 'No Files found!'
				);
				$status = 400;
			}
			return response()->json($data,$status);

		}

		public function sortbysize () { 



			$files = File::orderBy('size','DESC')->get();
			if(!$files->isEmpty()){
				return FileResource::collection( $files);
	
			}else{
				$data = array(
					'message' => 'No Files found!'
				);
				$status = 400;
			}
			return response()->json($data,$status);

		}




		
		public function sortbydates () { 



			$files = File::orderBy('created_at','ASC')->where('starredAt','!=',NULL)->get();
			if(!$files->isEmpty()){
				return FileResource::collection( $files);
	
			}else{
				$data = array(
					'message' => 'No Files found!'
				);
				$status = 400;
			}
			return response()->json($data,$status);

		}

		public function sortbynames () { 



			$files = File::orderBy('name','DESC')->where('starredAt','!=',NULL)->get();
			if(!$files->isEmpty()){
				return FileResource::collection( $files);
	
			}else{
				$data = array(
					'message' => 'No Files found!'
				);
				$status = 400;
			}
			return response()->json($data,$status);

		}

		public function sortbysizes () { 



			$files = File::orderBy('size','DESC')->where('starredAt','!=',NULL)->get();
			if(!$files->isEmpty()){
				return FileResource::collection( $files);
	
			}else{
				$data = array(
					'message' => 'No Files found!'
				);
				$status = 400;
			}
			return response()->json($data,$status);

		}


		
		public function sortbydatea () { 



			$files = File::orderBy('created_at','ASC')->where('archivedAt','!=',NULL)->get();
			if(!$files->isEmpty()){
				return FileResource::collection( $files);
	
			}else{
				$data = array(
					'message' => 'No Files found!'
				);
				$status = 400;
			}
			return response()->json($data,$status);

		}

		public function sortbynamea () { 



			$files = File::orderBy('name','DESC')->where('archivedAt','!=',NULL)->get();
			if(!$files->isEmpty()){
				return FileResource::collection( $files);
	
			}else{
				$data = array(
					'message' => 'No Files found!'
				);
				$status = 400;
			}
			return response()->json($data,$status);

		}

		
		public function sortbysizea () { 



			$files = File::orderBy('size','DESC')->where('archivedAt','!=',NULL)->get();
			if(!$files->isEmpty()){
				return FileResource::collection( $files);
	
			}else{
				$data = array(
					'message' => 'No Files found!'
				);
				$status = 400;
			}
			return response()->json($data,$status);

		}


				// ************************************ END Sorting Functions **********************************************//



 
		// get last files
		public function lastfiles () {

			$files = File::orderBy('created_at','DESC')->limit(3)->get();
			if(!$files->isEmpty()){
				return response()->json($files,200);

	
			}else{
				$data = array(
					'message' => 'No Files found!'
				);
				$status = 400;
			}
			return response()->json($data,$status);
		}
}
