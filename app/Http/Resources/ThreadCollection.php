<?php

namespace App\Http\Resources;

use App\Http\Controllers\ApiResponse\ApiResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ThreadCollection extends ResourceCollection
{
    use ApiResponse;

    public function toArray($request)
    {
        try {
            $data = $this->respondWithSuccessArray();
            $result = [
                'data'=>$this->collection,
            ];
            return array_merge($data , $result);
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }
}
