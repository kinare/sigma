<?php

namespace App\Http\Controllers\Sigma;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ApiRouter;

class UpstreamController extends Controller
{
    protected $apiWrapper;
    protected $apiPayload;
    protected $requestArray;
    protected $entity;

    public function index(Request $request, $entity, $payload = null)
    {
        //All sigma GET requests get handled here

        //Pass this to router - no intelligence (raw pass operation of entity plus payload)
        //inspect the entity

        //package the payload



        return $entity.'-'.$payload;
    }

    public function store(Request $request, $entity)
    {
        //All sigma POST requests get handled here
    }

    public function update(Request $request, $id)
    {
        //All PATCH operations here
    }

    public function destroy($id)
    {
        //
    }

    private function unpackageRequest (Request $request)
    {
        $this->apiWrapper = function () {};
    }
}
