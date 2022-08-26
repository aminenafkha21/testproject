<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'  =>  $this->id ,
            'name' =>  $this->name ,
            'size' => $this->format_size( $this->size )  ,
            'created_at' =>$this->created_at->format('Y-m-d H:i:s'),
            'archivedAt' => $this->format_date( $this->archivedAt )   ,
            'starredAt' =>  $this->format_date( $this->starredAt ) ,


        ]; 
    }



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
		  return round($size, 2) . ' ' . $unit    ;
		}
	  }	



      function format_date($date) {


        if($this->date != NULL  ) 
        { return $date->format('Y-m-d H:i:s') ;
        } else {
            return $date ;
        }


      }

}
