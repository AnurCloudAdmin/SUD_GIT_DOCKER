<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Logs extends Model
{
  protected $table='logs';
  public function getLink(): BelongsTo
    {
        $get_object =  $this->belongsTo(Link::class,'app_no','proposal_no');
        return $get_object;
    }
}
