<?php

namespace KTL\Sigma\Wrapper;

use Exception;
use KTL\Sigma\Models\SigmaWrapper;
use KTL\Sigma\Transport\Exceptions\ApiException;
use KTL\Sigma\Transport\Transport;

class Wrapper implements WrapperInterface
{
    protected $keys = [];

    public $mapper = [];

    public $method;

    public $payload;

    public $wrapper;

    public $entity;

    public function __construct($wrapper, $payload, $method)
    {
        $this->payload = $payload;
        $this->wrapper = $wrapper;
        $this->entity = $wrapper->downStreamID;
        $this->setKeys($wrapper->fields()->get()->toArray());
        $this->mapper = $this->setMappings($wrapper);
        $this->method = $method;
    }

    /**
     * @param $fields
     */
    public function setKeys($fields)
    {
        foreach ($fields as $field){
            if ($field['Key'])
                $this->keys[$field['UpStreamFieldID']] = $field['DownStreamFieldID'];
        }
    }

    /**
     * @param bool $flip
     * @return array
     */
    public function map($flip = false): array
    {
        $mapper = $this->mapper;

        if (empty($mapper))
            return $this->payload;

        if (empty($this->payload))
            return [];

        /* to allow filters for v1.0.0 */
        if ($this->method === 'get' && !empty($this->payload) && !$flip){
            return $this->payload;
        }

        if ($flip)
            $mapper = $this->flip($mapper);

        return $this->setValues($this->payload, $mapper);
    }

    /**
     * @param array $data
     * @param array $mapper
     * @return array
     */
    public function setValues(array $data, array $mapper): array
    {
        $mapped = [];

        /*loop through mapper check if data has key and assign to mapper*/
        foreach ($mapper as $key => $value){
            if (array_key_exists($key, $data))
                if (is_array($value) && is_array($data[$key])){

                    if (isset($data[$key][0]) && is_array($data[$key][0])){
                        $innerMapped = [];
                        foreach ($data[$key] as $datum){
                            array_push($innerMapped, $this->setValues($datum, $value));
                        }
                        $mapped[$key] = $innerMapped;
                    }else{
                        $mapped[$key] = $this->setValues($data[$key], $value);
                    }
                }else{
                    $mapped[$value] =  $data[$key] ?? '';
                }
        }

        return $mapped;
    }

    /**
     * @param array $map
     * @return array
     */
    public function flip(array $map): array
    {
        $flipped = [];

        foreach ($map as $key => $value){
            if (is_array($value)){
                $flipped[$key] = $this->flip($value);
            }else{
                $flipped[$value] = $key;
            }
        }
        return $flipped;
    }

    /**
     * @param $payload
     */
    public function setPayload($payload): void
    {
        $this->payload = $payload;
    }

    /**
     * @param $wrapper
     */
    public function setMappings($wrapper)
    {
        $map = [];

        $children = $wrapper->children() ?: [];
        $map = $this->getFields($wrapper);

        foreach ($children as $child){
            $childWrapper = SigmaWrapper::where('entityId', $child)->first();
            if ($childWrapper)
                $map[$childWrapper->downStreamID] = !empty($childWrapper->children()) ? $this->setMappings($childWrapper) : $this->getFields($childWrapper);
        }

        return $map;
    }

    public function getFields($wrapper): array
    {
        $wrapperFields = [];
        $fields = $wrapper->fields()->get()->toArray();
        foreach ($fields as $field){
            $field = (object) $field;
            $wrapperFields["$field->UpStreamFieldID"] = "$field->DownStreamFieldID";
        }
        return $wrapperFields;
    }

    /**
     * @throws Exception
     */
    public function validateOperation()
    {
        switch (mb_strtoupper($this->method))
        {
            case "GET":
                if (!$this->wrapper->Get) throw new Exception('Get Operation not allowed');
                break;
            case "POST":
                if (!$this->wrapper->Insert) throw new Exception('Post Operation not allowed');
                break;
            case "PATCH":
                if (!$this->wrapper->Edit) throw new Exception('Patch Operation not allowed');
                $this->entity = $this->entity.$this->getKeyString();
                break;
            case "DELETE":
                if (!$this->wrapper->Delete) throw new Exception('Delete Operation not allowed');
                $this->entity = $this->entity.$this->getKeyString();
                $this->payload = [];
                break;
            case "CU":
                break;
            default:
                throw new Exception('Unspecified operation');
        }
    }

    /**
     * @param Transport $transport
     * @param null $company
     * @return array
     * @throws ApiException
     */
    public function execute(Transport $transport, $company = null): array
    {
        $response = [];
        if (mb_strtolower($this->method )=== 'cu' && method_exists($transport, 'cu'))
            return $transport->cu($this->entity, $this->map(), $company);

        $res = $transport->request($this->entity, $this->map(), $this->method);

        foreach ($res as $key => $value){
            $this->setPayload($value);
            $response[] = $this->map(true);
        }
        return $response;
    }

    /**
     * @return string
     */
    public function getKeyString(): string
    {
        /* set keys */
        $keyString = '';
        foreach ($this->keys as $upStreamKey => $downStreamKey){
            if ($keyString !== '')
                $keyString .= ',';

            $keyString .= $downStreamKey."=".  (is_string($this->payload[$upStreamKey])
                    ?  "'".$this->payload[$upStreamKey]."'"
                    : $this->payload[$upStreamKey]);
        }
        return "($keyString)";
    }
}
