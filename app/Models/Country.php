<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        // adicione outros campos conforme necessário
    ];
    use HasFactory;

    /**
     * Relationships
     */
    public function entities()
    {
        return $this->hasMany(Entity::class);
    }

    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}
