<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class FriendController extends Controller {

    // Create a view to list our friends
    public function getIndex() {

        // Set $friends to the friends function in Auth::user model
        $friends = Auth::user()->friends();
        $requests = Auth::user()->friendRequests();

        // Return friends list with friends with requests to be a friend
        return view('friends.index')
            ->with('friends', $friends)->with('requests', $requests);
    }


    /**
     * Add a Friend.
     */
    public function getAdd($username) {

        $user = User::where('username', $username)->first();

        // Check if user can be found, if not, redirect
        if(!$user) {
            return redirect()->route('home')->with('info', 'That user could not be found.');
        }

        // If user tries to add himself has friend, then redirect home
        if (Auth::user()->id === $user->id) {
            return redirect()->route('home');
        }

        // If currently Authenticated user has friend request pending
        // OR if the other user has a friend request pending from us
        if (Auth::user()->hasFriendRequestPending($user)
            || $user->hasFriendRequestPending(Auth::user())) {
                return redirect()->route('profile.index', ['username' => $user->username])
                    ->with('info', 'Friend request already pending');
        }

        // Checking if we are already friends
        if (Auth::user()->isFriendsWith($user)) {
            return redirect()->route('profile.index', ['username' => $user->username])
                ->with('info', 'You are already friends');
        }

        // Add a friend request with redirect.
        Auth::user()->addFriend($user);
        return redirect()->route('profile.index', ['username' => $username])
            ->with('info', 'Friend request sent.');
    }


    /**
     * Accept a users Friend Request
     */
    public function getAccept($username) {
        $user = User::where('username', $username)->first();

        // Check if user can be found, if not, redirect
        if(!$user) {
            return redirect()->route('home')->with('info', 'That user could not be found.');
        }

        // If we received a friend request from this user
        if (!Auth::user()->hasFriendRequestReceived($user)) {
            return redirect()->route('home');
        }

        // else accept friend request
        Auth::user()->acceptFriendRequest($user);

        return redirect()->route('profile.index', ['username' => $username])
            ->with('info', 'Friend request accepted.');

    }

    /**
     * Delete Friend.
     * -- pass in $username from Delete Friend Form --
     */
    public function postDelete($username) {
        // Set $user = where User = the username being deleted.
        $user = User::where('username', $username)->first();

        // Checking if we are already friends
        if (!Auth::user()->isFriendsWith($user)) {
            return redirect()->back();
        }

        // Delete friend
        Auth::user()->deleteFriend($user);
        return redirect()->back()->with('info', 'Friend deleted.');

    }

}