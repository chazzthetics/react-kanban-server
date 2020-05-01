<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    protected $fillable = [
        'uuid', 'title', 'completed',
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];

    protected $hidden = [
        'id', 'checklist_id', 'created_at', 'updated_at',
    ];

    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }
}
