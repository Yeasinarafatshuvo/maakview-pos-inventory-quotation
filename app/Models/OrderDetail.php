<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use SoftDeletes;

    protected $fillable = ['order_id', 'product_id', 'product_variation_id', 'price','actual_price', 'tax', 'total', 'quantity','updated_at','prod_serial_num'];

    public function order()
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class,'product_variation_id');
    }


}
