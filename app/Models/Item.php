<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Item
 * 
 * @property int $id
 * @property string|null $name
 * @property string $sub_name
 * @property string $price
 * @property string|null $description
 * @property string|null $farbrics
 * @property string|null $detail
 * @property int $is_hot
 * @property string|null $img_thumb
 * @property string|null $material
 * @property string|null $color
 * @property string|null $size
 * @property int|null $category_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Item extends Model
{
	protected $table = 'item';

	protected $casts = [
		'is_hot' => 'int',
		'category_id' => 'int'
	];

	protected $fillable = [
		'name',
		'sub_name',
		'price',
		'slug',
		'description',
		'farbrics',
		'detail',
		'is_hot',
		'price_setting',
		'img_thumb',
		'material',
		'color',
		'size',
		'category_id'
	];

	public function itemImages()
	{
		return $this->hasMany(ItemImage::class);
	}

	public function category()
	{
		return $this->belongsTo(Category::class);
	}
}
