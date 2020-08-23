<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    protected $table = 'products';
	protected $columns = ['id', 'name', 'stock'];
}
