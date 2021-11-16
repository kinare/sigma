<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use KTL\Sigma\Models\SigmaField;
use KTL\Sigma\Models\SigmaProvider;
use KTL\Sigma\Models\SigmaWrapper;
use KTL\Sigma\Transport\BCTransport;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('sigma/index', [
            'providers' => SigmaProvider::all(),
        ]);
    }

    /* pulls sigma configs from sigma bc */
    public function init()
    {
        try {
            $transport = new BCTransport(env('SIGMA_URL'), env('SIGMA_USER'), env('SIGMA_PASSWORD'));

            DB::beginTransaction();

            /* get providers */
            $providers = $transport->request(config('sigma.api.provider'));


            /* clear current */
            SigmaProvider::truncate();

            foreach ($providers as $provider){
                $p = new SigmaProvider();
                $p->fill((array) $provider);
                $p->save();
            }

            /* get wrappers */
            $wrappers =  $transport->request(config('sigma.api.wrappers'));

            /* clear current */
            SigmaWrapper::truncate();

            foreach ($wrappers as $wrapper){
                $w = new SigmaWrapper();
                $w->fill((array) $wrapper);
                $w->save();
            }

            /* get wrappers */
            $fields =  $transport->request(config('sigma.api.fields'));

            /* clear current */
            SigmaField::truncate();

            foreach ($fields as $field){
                $f = new SigmaField();
                $f->fill((array) $field);
                $f->save();
            }
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function refresh()
    {
        $this->init();
        return $this->index();
    }

}
