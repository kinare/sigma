<?php

namespace KTL\Sigma\Wrapper;

use KTL\Sigma\Transport\Transport;

class Wrapper implements WrapperInterface
{
    protected $keys = [];

    public $mapper = [];

    public $method;

    public $payload;

    public $wrapper;

    public $entity;

    public function __construct($wrapper, $entity, $payload, $method)
    {
        $this->payload = $payload;
        $this->wrapper = $wrapper;
        $this->entity = $entity;
        $this->setKeys($wrapper->fields()->get()->toArray());
        $this->setMappings($wrapper->fields()->get()->toArray());
        $this->method = $method;
    }

    public function setKeys($fields)
    {
        foreach ($fields as $field){
            if ($field['Key'])
                $this->keys[$field['UpStreamFieldID']] = $field['DownStreamFieldID'];
        }
    }

    public function map($transpose = false): array
    {
        $mapper = $this->mapper;

        if (empty($mapper))
            return $this->payload;

        if (empty($this->payload))
            return [];

        /* to allow filters for v1.0.0*/
        if ($this->method === 'get'){
            return $this->payload;
        }


        if ($transpose)
            $mapper = $this->transpose($mapper);

        $data = $this->setValues($this->payload, $mapper);

        $payload = [];

        /* cleanup payload */
        foreach ($data as $key => $value){
            if (!empty($data[$key]))
                $payload[$key] = $value;
        }

        return $payload;
    }

    public function setValues(array $data, array $mapper): array
    {
        $mapped = [];

        /*loop through mapper check if data has key and assign to mapper*/
        foreach ($mapper as $key => $value){
            if (is_array($value) && is_array($data[$key])){
                $mapped[$key] = $this->setValues($data[$key], $value);
            }else{
                $mapped[$value] = $data[$key] ?? '';
            }
        }

        return $mapped;
    }

    public function transpose(array $map): array
    {
        $transposed = [];

        foreach ($map as $key => $value){
            if (is_array($value)){
                $transposed[$key] = $this->transpose($value);
            }else{
                $transposed[$value] = $key;
            }
        }
        return $transposed;
    }

    public function setPayload($payload): void
    {
        $this->payload = $payload;
    }

    public function setMappings($fields)
    {
        foreach ($fields as $field){
            $field = (object) $field;
            $this->mapper["$field->UpStreamFieldID"] = "$field->DownStreamFieldID";
        }
    }

    public function validateOperation()
    {
        switch (mb_strtoupper($this->method))
        {
            case "GET":
                if (!$this->wrapper->Get) throw new \Exception('Operation not allowed');
                break;
            case "POST":
                if (!$this->wrapper->Insert) throw new \Exception('Operation not allowed');
                break;
            case "PATCH":
                if (!$this->wrapper->Edit) throw new \Exception('Operation not allowed');
                $this->entity = $this->entity.$this->getKeyString();
                break;
            case "DELETE":
                if (!$this->wrapper->Delete) throw new \Exception('Operation not allowed');
                $this->entity = $this->entity.$this->getKeyString();
                $this->payload = [];
                break;
            default:
                throw new \Exception('Unspecified operation');
        }
    }

    public function execute(Transport $transport)
    {
        $response = [];
        /* res is array[array]*/
        $res = $transport->request($this->entity, $this->map(), $this->method);
        foreach ($res as $key => $value){
            $this->setPayload($value);
            $response[] = $this->map(true);
        }
        return $response;
    }

    public function getKeyString()
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
