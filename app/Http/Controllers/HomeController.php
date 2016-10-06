<?php

namespace App\Http\Controllers;
use App\Models\Status;
use Auth;

class HomeController extends Controller {

    public function index() {

        // If user is logged in, return this view with you and your friends statuses
        if (Auth::check()) {
            // Set $statuses = to Status Model AND function notReply, (which means statuses that
            // are NOT replied to ), and
            // where (pass in $query builder to pass things in)
            // return query where user id matches our user id OR
            // when the user-id of each record in our "friends" list
            $statuses = Status::notReply()->where(function($query) {
                return $query->where('user_id', Auth::user()->id)
                    ->orWhereIn('user_id', Auth::user()->friends()->lists('id'));
            })
            // Then order by created_at by desc, and also paginate them by 10
            ->orderBy('created_at', 'desc')->paginate(10);

            return view('timeline.index')->with('statuses', $statuses);
        }

        // Else return this view
        return view('home');
    }
}