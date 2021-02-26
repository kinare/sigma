<?php

namespace KTL\Sigma\Models;

use Illuminate\Database\Eloquent\Model;

class SigmaProvider extends Model
{
    protected $guarded = [];

    protected $appends = ['wrappers'];

    public function getWrappersAttribute()
    {
        return $this->wrapper()->get();
    }

    public function wrapper()
    {
        return $this->hasMany('KTL\Sigma\Models\SigmaWrapper', 'provider', 'Provider');
    }

}
