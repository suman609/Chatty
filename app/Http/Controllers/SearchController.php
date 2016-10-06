<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Models\User;

class SearchController extends Controller {

    public function getResults(Request $request) {

        // Check if query is there
        $query = $request->input('query');

        // If not...
        if(!$query) {
            return redirect()->route('home');
        }

        /**
         * This is going to be our search query.
         * We will search by first and last name
         * OR by username
         */
        $users = User::where(DB::raw("CONCAT(first_name, ' ', last_name)"),
            'LIKE', "%{$query}%")->orWhere('username', 'LIKE', "%{$query}%")->get();


        // Else return results
        return view('search.results')->with('users', $users);
    }
}