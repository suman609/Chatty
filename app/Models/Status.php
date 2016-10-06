<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Status extends Model {

    // Make the statuses table only to this class
    protected $table = 'statuses';

    // The fillable property specifies which attributes should be mass-assignable.
    protected $fillable = [
        'body'
    ];

    /**
     * Relationship to relate back to the user
     */
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * This will select any statuses that are NOT replies
     * -- pass in the $query builder
     */
    public function scopeNotReply($query) {
        // Return $query where the parent_id = NULL in statuses table
        return $query->whereNull('parent_id');
    }

    /**
     * Set up a reply relationship
     * Can have many replies with the parent_id in status table
     */
    public function replies() {
        return $this->hasMany('App\Models\Status', 'parent_id');
    }

    /**
     * Grab who liked a status
     * -- You could use this in the User Model to like Users if you want --
     */
    public function likes() {
        // This will morph Many likes to pick up what Model and ID
        // you are talking about.
        // "likeable" coming from the Like model with public function "likeable"
        return $this->morphMany('App\Models\Like', 'likeable');
    }

}