<?php

namespace App\Models;

use App\Models\Status;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Model implements AuthenticatableContract

{
    use Authenticatable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'location'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * Get the Users full Name
     */
    public function getName() {

        // If first and last names are submitted, then return first and last name
        if ($this->first_name && $this->last_name) {
        return "{$this->first_name} {$this->last_name}";
        }

        // If first name is submitted, then return first name
        if ($this->first_name) {
            return $this->first_name;
        }

        // else return null
        return null;
    }

    /**
     * Get the name OR username when signed in.
     */
    public function getNameOrUsername() {
        return $this->getName() ? : $this->username;
    }

    /**
     * Just get the first name OR username.
     * Difference is its going to get just the first name
     * instead of first and last name OR Username
     */
    public function getFirstNameOrUsername() {
        return $this->first_name ? : $this->username;
    }

    /*
     * Implement Gravatar into a users Profile Picture by default.
     */
    public function getAvatarUrl() {
        return "http://www.gravatar.com/avatar/{{ md5($this->email) }}
                ?d=mm&s=40";
    }

    /**
     * User can have many statuses
     *  so set hasMany relationship to the Models/Status -> user_id field
     * foreign key in table statuses relates to user id in users table
     */
    public function statuses() {
        return $this->hasMany('App\Models\Status', 'user_id');  // user_id coming from statues table
    }

    /**
     * A User owns a Like
     * so a user can have many likes
     */
    public function likes() {
        return $this->hasMany('App\Models\Like', 'user_id');
    }

    /**
     * Friends of the logged in user
     * belongsToMany is defined by laravel by a Many To Many relationship
     * So you ('user_id') can have many friends('friend_id')
     */
    public function friendsOfMine() {
        return $this->belongsToMany('App\Models\User', 'friends', 'user_id', 'friend_id');
    }

    /**
     * Users who have this user ( or me ) as a friend
     * belongsToMany is defined by laravel by a Many To Many relationship
     * So your friend ('friend_id') can have you('user_id') as a friend
     */
    public function friendOf() {
        return $this->belongsToMany('App\Models\User', 'friends', 'friend_id', 'user_id');
    }

    /**
     * Current users friends
     */
    public function friends() {
        // Return all the friends of mine where accepted in the DB = true
        // -
        // then get the results and merge them with function friendOf
        // to make accepted = true
        // This is so if we send friend request to a user, and they accept,
        // so that other user is friends with you also.. Its like a 1 to 1 relationship.
        return $this->friendsOfMine()->wherePivot('accepted', true)
            ->get()->merge($this->friendOf()->wherePivot('accepted', true)->get());
    }


    /**
     * Grab friend requests
     */
    public function friendRequests() {
        // Returns all the friends of mine
        // where accepted = false
        // false, means you added someone
        return $this->friendsOfMine()
            ->wherePivot('accepted', false)->get();
    }


    /**
     * Get any pending friend requests
     */
    public function friendRequestsPending() {
        // Returns a pending friend requests to me
        // where accepted = false
        // false, means someone added YOU
        return $this->friendOf()->wherePivot('accepted', false)->get();
    }

    /**
     * Check if a user has a friend request pending from other user
     */
    public function hasFriendRequestPending(User $user) {
        // (bool) is meant for true or false
        // Return pending friend requests where the id of the
        // user = the user id we pass in
        return (bool) $this->friendRequestsPending()->where('id', $user->id)->count();
    }

    /**
     * Check to see if we received a friend request from another user
     */
    public function hasFriendRequestReceived(User $user) {
        // (bool) is meant for true or false
        // Return friend requests where the id = user id
        return (bool) $this->friendRequests()->where('id', $user->id)->count();
    }

    /**
     * To add a friend
     */
    public function addFriend(User $user) {
        // attach the users id to friendOf
        $this->friendOf()->attach($user->id);
    }


    /**
     * To delete a friend
     */
    public function deleteFriend(User $user) {
        // attach the users id to friendOf
        $this->friendOf()->detach($user->id);
        //$this->friendsOfMine()->detach($user->id);
    }

    /**
     * Accept a friend request
     */
    public function acceptFriendRequest(User $user) {

        // Grab the friend requests where the id = the user id
        // then grab that user and update the pivot (table)
        // and update array to accepted = true (or = 1)
        $this->friendRequests()->where('id', $user->id)
        ->first()->pivot->update(['accepted' => true]);
    }

    /**
     * Tells us if we are friends with a particular user
     */
    public function isFriendsWith(User $user) {
        // Returns current users friends where id = the user id
        return (bool) $this->friends()->where('id', $user->id)->count();
    }

    /**
     * Check to see if a User has Liked a Status once, if they
     * did, put in place a solution so users cant like a status again.
     */
    public function hasLikedStatus(Status $status) {

        // Return a boolean with $status set to likes
        // where the 'likeable_id' = the status ID
        // and where the 'likeable_type' = to the class name to the status we pass in
        //return (bool) $status->likes
            //->where('likeable_id', $status->id)
            //->where('likeable_type', get_class($status))
            //->where('user_id', $this->id)
            //->count();

        // OR You can do it this way
        // Return the status likes where the user_id = the current users ID, then count.
        return (bool) $status->likes->where('user_id', $this->id)->count();
    }



}