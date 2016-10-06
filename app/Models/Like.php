<?php


namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Like extends Model {

    protected $table = 'likeable';

    /**
     * Set method likeable to return a
     * polymorphic relationship.
     */
    public function likeable() {
        // "morphTo" means to can be applied to any Model
        //// It is a polymorphic relationship.
        return $this->morphTo();
    }

    /**
     * Set a relationship to see who liked something
     */
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}