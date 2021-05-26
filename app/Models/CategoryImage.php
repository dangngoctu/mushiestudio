<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CategoryImage
 * 
 * @property int $id
 * @property string|null $url
 * @property int|null $category_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class CategoryImage extends Model
{
	protected $table = 'category_images';

	protected $casts = [
		'category_id' => 'int'
	];

	protected $fillable = [
		'url',
		'category_id'
	];
}
