<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use App\Models\User;
use Auth;

class StatusController extends Controller {

    // This will handle validation and posting of the status.
    public function postStatus(Request $request) {

        $this->validate($request, [
            'status' => 'required|max:1000',
        ]);

        // Create a status
        // statuses is comming from user model
        Auth::user()->statuses()->create([
            'body' => $request->input('status'),
        ]);

        return redirect()->route('home')->with('info', 'Status posted.');
    }

    /**
     *
     */
    public function postReply(Request $request, $statusId) {
        // Validate the Reply for each status ID - {$statusId}
        $this->validate($request, [
            "reply-{$statusId}" => 'required|max:1000',
        ], [
            'required' => 'The reply body is required.'
        ]);

        // Set $status to Status::notReply, also find the status ID we need to reply to.
        $status = Status::notReply()->find($statusId);

        // Check if that status exists
        if (!$status) {
            return redirect()->route('home');
        }

        // Check if currently Authenticated user is friends with the
        // users whose status this is, AND
        // check to see if we are replying to our own status,
        // if we are not, then redirect home.
        if (!Auth::user()->isFriendsWith($status->user)
            && Auth::user()->id !== $status->user->id) {
                return redirect()->route('home');
        }

        // Reply to a status
        // Create a status with a body, with the unique status ID
        // then associate us with the reply
        $reply = Status::create([
            'body' => $request->input("reply-{$statusId}"),
        ])->user()->associate(Auth::user());

        // Save the reply
        $status->replies()->save($reply);

        return redirect()->back();

    }

    /**
     * Like a status
     */
    public function getLike($statusId) {
        // Find the Status ID.
        $status = Status::find($statusId);

        // If no status ID is found, redirect.
        if (!$status) {
            return redirect()->route('home');
        }

        // Check if the user making the like is friends with the user
        // that has posted the status
        if (!Auth::user()->isFriendsWith($status->user)) {
            return redirect()->route('home');
        }

        // Check if they already liked the status.
        // -- we use hasLikedStatus from USer Model --
        if (Auth::user()->hasLikedStatus($status)) {
            return redirect()->back();
        }

        // Create the Like in the DB Table.
        $like = $status->likes()->create([]);

        // Save to the current users likes.
        Auth::user()->likes()->save($like);

        return redirect()->back();


    }



}