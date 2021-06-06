<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Category
 * 
 * @property int $id
 * @property string $name
 * @property string $url
 * @property int $menu_id
 * @property int $type
 * @property string $video
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Category extends Model
{
	use SoftDeletes;
	protected $table = 'category';

	protected $casts = [
		'menu_id' => 'int',
		'type' => 'int'
	];

	protected $fillable = [
		'name',
		'url',
		'menu_id',
		'type',
		'video',
		'img',
		'description',
	];

	public function menu()
	{
		return $this->belongsTo(Menu::class);
	}

	public function categoryImages()
	{
		return $this->hasMany(CategoryImage::class);
	}
}
