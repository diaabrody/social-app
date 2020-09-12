<?php

namespace App\Http\Controllers;

use App\ApiCode;
use App\Http\Requests\createThreadRequest;
use App\Http\Resources\ThreadCollection;
use App\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api'])->except(['index']);
    }

    /**
     * @return ThreadCollection
     */
    public function index()
    {
        return new ThreadCollection(Thread::paginate(20));
    }

    /**
     * @param \App\Http\Controllers\CreateThreadRequest $request
     */
    public function store(CreateThreadRequest $request){
       $data = $request->getAttributes();
        try {
            Thread::persist($data);
        }catch (\Exception $e){
            report($e);
            return $this->respondWithUnexpectedError('Error while saving the post , please try again later');
        }
    }
}
