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

    public function fields(array $expands = [])
    {
        return $this->hasMany('KTL\Sigma\Models\SigmaField', 'EntityID', 'entityID');
    }

    public function children(): array
    {
        $children = SigmaChildWrappers::where('EntityId', $this->entityID)->get();
        $childWrappers = [];
        foreach ($children as $child){
            $childWrappers[] = $child->Wrapper;
        }
        return $childWrappers;
    }
}
