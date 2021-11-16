<?php


namespace KTL\Sigma\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use KTL\Sigma\Facade\Sigma;
use KTL\Sigma\Http\Controllers\Concerns\ApiResponder;
use KTL\Sigma\Models\SigmaWrapper;

class SigmaBaseController extends Controller
{
    use ApiResponder;

    public $provider;
    public $entity;

    public function index(Request $request) {
        try {
            return $this->success(Sigma::request($this->provider, $this->entity, $this->filter($request->all())));
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }

    public function store(Request $request)
    {
        try {
            return $this->success(Sigma::request($this->provider, $this->entity, $request->all(), 'post'));
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }

    public function update (Request $request) {
        try {
            return $this->success(Sigma::request($this->provider, $this->entity, $request->all(), 'patch'));
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }

    public function destroy(Request $request) {
        try {
            return $this->success(Sigma::request($this->provider, $this->entity, $request->all(), 'delete'));
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }

    public function filter(array $data, $operand = 'and'): array
    {
        $wrapper = $this->sigmaWrapper = SigmaWrapper::where([
            'upStreamID' => $this->entity,
            'provider' => $this->provider
        ])->first();

        $fields = $wrapper->fields()->get()->toArray();

        $mapper = [];

        foreach ($fields as $field){
            $field = (object) $field;
            $mapper["$field->UpStreamFieldID"] = "$field->DownStreamFieldID";
        }

        $mapped = [];
        foreach ($mapper as $key => $value){
            if (array_key_exists($key, $data))
                $mapped[$value] = $data[$key];
        }

        $filter = '';
        foreach ($mapped as $key => $value){
            if ($filter !== '') $filter .= " $operand ";
            $filter .= "$key eq ". (is_string($value) ?  "'".$value."'" : $value);
        }

        return $filter === '' ? [] : ['$filter' => $filter];
    }
}
