<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Color
 * 
 * @property int $id
 * @property string $name
 * @property string $color_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Color extends Model
{
	use SoftDeletes;
	protected $table = 'color';

	protected $fillable = [
		'name',
		'color_code'
	];
}
