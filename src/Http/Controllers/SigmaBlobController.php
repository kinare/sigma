<?php

namespace KTL\Sigma\Http\Controllers;

use App\Http\Controllers\Controller;
use KTL\Sigma\Http\Controllers\Concerns\ApiResponder;
use Illuminate\Http\Request;
use KTL\Sigma\Facade\Sigma;

class SigmaBlobController extends Controller
{
    use ApiResponder;

    public function file(Request $request)
    {
        try {
            return $this->success( Sigma::request(
                mb_strtoupper($request->route()->parameter('provider')),
                $this->entity = $request->route()->parameter('entity'),
                $request->all(),
                'CU'
            ));
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }
}
