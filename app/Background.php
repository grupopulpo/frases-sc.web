<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use  Illuminate\Database\Eloquent\SoftDeletes;
class Background extends Model{
	use SoftDeletes;

	protected $table='backgrounds';
	 protected $fillable = [
        'name','url'];


	public function phrases(){
		return $this->hasMany(Prase::class,'background_id');
	}
}