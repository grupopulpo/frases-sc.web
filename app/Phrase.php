<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use  Illuminate\Database\Eloquent\SoftDeletes;
class Phrase extends Model{
	use SoftDeletes;

	protected $table='phrases';
	 protected $fillable = [
	'background_id',
	'user_id',
	'content',
	'fonts',
	'color_text','url_phrase',
	'approved'];

	public function userCreate(){
		return $this->belongsTo(User::class,'user_id');
	}
	public function background(){
		return $this->belongsTo(Background::class,'background_id');
	}
	public function userApproved(){
		return $this->belongsTo(User::class,'user_approved');
	}
}