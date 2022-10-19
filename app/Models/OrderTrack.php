<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AdminLog;
class OrderTrack extends Model
{
    
    use AdminLog;

	protected $fillable = ['order_id', 'title','text'];

    public function order()
    {
        return $this->belongsTo('App\Models\Order','order_id')->withDefault(function ($data) {
			foreach($data->getFillable() as $dt){
				$data[$dt] = __('Deleted');
			}
		});
    }

}
