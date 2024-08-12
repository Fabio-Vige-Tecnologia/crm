<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Customer extends Model {
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
    ];

    /*
     * MUTATORS
     */
    public function getCreatedAtAttribute(string $value): ?string {
        return $value ? Carbon::parse($value)->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i:s') : null;
    }

    public function setCreatedAtAttribute(string $value): void {
        $this->attributes['created_at'] = $value ? Carbon::parse($value)->setTimezone('America/Sao_Paulo') : null;
    }

    public function getUpdatedAtAttribute(string $value): ?string {
        return $value ? Carbon::parse($value)->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i:s') : null;
    }

    public function setUpdatedAtAttribute(string $value): void {
        $this->attributes['updated_at'] = $value ? Carbon::parse($value)->setTimezone('America/Sao_Paulo') : null;
    }

    public function getDeletedAtAttribute(?string $value): ?string {
        return $value ? Carbon::parse($value)->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i:s') : null;
    }

    public function setDeletedAtAttribute(?string $value): void {
        $this->attributes['deleted_at'] = $value ? Carbon::parse($value)->setTimezone('America/Sao_Paulo') : null;
    }
}
