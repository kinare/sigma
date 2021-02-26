<?php

namespace KTL\Sigma\Models;

use Illuminate\Database\Eloquent\Model;

class SigmaWrapper extends Model
{

    protected $guarded = [];

    protected $appends = ['fields'];


    public function getFieldsAttribute()
    {
        return $this->fields()->get();
    }

    public function fields()
    {
        return $this->hasMany('KTL\Sigma\Models\SigmaField', 'EntityID', 'entityID');
    }
}
