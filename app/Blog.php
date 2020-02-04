<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    /**
     * @var string
    */
    protected $table = 'blogs';

    /**
     * @var array
    */
    protected $guarded = [];

    /**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	*/
	public function blogs()
	{
	    return $this->hasMany(Blog::class);
	}
}
