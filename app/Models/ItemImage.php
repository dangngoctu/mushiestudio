<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ItemImage
 * 
 * @property int $id
 * @property string|null $url
 * @property int|null $item_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class ItemImage extends Model
{
	protected $table = 'item_images';

	protected $casts = [
		'item_id' => 'int'
	];

	protected $fillable = [
		'url',
		'item_id'
	];
}
