<?php

namespace KTL\Sigma\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use KTL\Sigma\Models\SigmaChildWrapper;
use KTL\Sigma\Models\SigmaChildWrappers;
use KTL\Sigma\Models\SigmaField;
use KTL\Sigma\Models\SigmaProvider;
use KTL\Sigma\Models\SigmaWrapper;
use KTL\Sigma\Transport\BCTransport;

class SigmaController extends Controller
{
    /**
     * @return Response
     */
    public function index()
    {
        return Inertia::render('sigma/index', [
            'providers' => SigmaProvider::all(),
        ]);
    }

    /**
     * pulls sigma configs from sigma bc
     * @return \Illuminate\Http\JsonResponse
     */
    public function init()
    {
        try {

            $transport = new BCTransport(
                config('sigma.connection.base_url'),
                config('sigma.connection.username'),
                config('sigma.connection.password')
            );

            DB::beginTransaction();

            /* set provider filter */
            $services = config('sigma.services')
                ? explode(',', config('sigma.services'))
                : [] ;

            $serviceFilter = '';
            foreach ($services as $service){
                if ($serviceFilter !== '')
                    $serviceFilter .= ' or ';
                $serviceFilter .= "Provider eq ". ("'".mb_strtoupper($service)."'");
            }

            SigmaProvider::truncate();

            /* get available service providers */
            $providers = $transport->request(
                config('sigma.api.provider'),
                $serviceFilter ? ['$filter' => $serviceFilter]: []
            );

            $wrapperFilter = '';
            foreach ($providers as $provider){
                $p = new SigmaProvider();
                $p->fill((array) $provider);
                $p->save();

                /* set wrapper filters */
                if ($wrapperFilter !== '')
                    $wrapperFilter .= ' or ';
                $wrapperFilter .= "provider eq ". ("'".mb_strtoupper($provider['Provider'])."'");
            }

            SigmaWrapper::truncate();

            /* get wrappers for available providers */
            $wrappers =  $transport->request(
                config('sigma.api.wrappers'),
                $wrapperFilter ? ['$filter' => $wrapperFilter]: []
            );

            $fieldFilter = '';
            $childWrapperFilter = '';
            foreach ($wrappers as $wrapper){
                $w = new SigmaWrapper();
                $w->fill((array) $wrapper);
                $w->save();

                /* set field filters */
                if ($fieldFilter !== '')
                    $fieldFilter .= ' or ';
                $fieldFilter .= "EntityID eq ". ("'".mb_strtoupper($wrapper['entityID'])."'");

                if ($childWrapperFilter !== '')
                    $childWrapperFilter .= ' or ';
                $childWrapperFilter .= " EntityId eq ". ("'".mb_strtoupper($wrapper['entityID'])."'");
            }

            SigmaChildWrappers::truncate();
            $childWrappers = $transport->request(
                config('sigma.api.childWrappers'),
                $childWrapperFilter ? ['$filter' => $childWrapperFilter] : []
            );


            foreach ($childWrappers as $childWrapper) {
                $cw = new SigmaChildWrappers();
                $cw->fill((array) $childWrapper);
                $cw->save();
            }

            SigmaField::truncate();

            /* get fields for available wrappers */
             $fields =  $transport->request(
                 config('sigma.api.fields'),
                 $fieldFilter ? ['$filter' => $fieldFilter]: []
             );

             foreach ($fields as $field){
                 $f = new SigmaField();
                 $f->fill((array) $field);
                 $f->save();
             }

            DB::commit();
        }catch (\Exception $exception){
            dump($exception);
            DB::rollBack();
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Re-sync sigma metadata from BC
     * @return Response
     */
    public function refresh()
    {
        $this->init();
        return $this->index();
    }
}
