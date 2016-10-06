<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller {

    // Show a Users Profile
    public function getProfile($username) {
        // Check if user exists
        // Set 'username' = $username, with first result
        $user = User::where('username', $username)->first();
        // If not, return abort page.
        if(!$user) {
            abort(404);
        }

        // Grab ALL of the currently logged in users statuses,
        // DONT include the replies to those statuses
        // -- This is to show all the users statuses on the profile page
        $statuses = $user->statuses()->notReply()->get();

        // Else return view
        // with the user
        // and his statuses
        // AND check if is the user currently viewing this profile our friend?
        return view('profile.index')
            ->with('user', $user)
            ->with('statuses', $statuses)
            ->with('authUserIsFriend', Auth::user()->isFriendsWith($user));
    }

    /**
     * Get the Profile view
     */
    public function getEdit() {
        return view('profile.edit');
    }



    // Access our request information
    public function postEdit(Request $request) {
        $this->validate($request, [
            'first_name' => 'alpha|max:50',
            'last_name' => 'alpha|max:50',
            'location_name' => 'alpha|max:20',
        ]);

        // Access currently Authenticated user and update columns
        Auth::user()->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'location' => $request->input('location')
        ]);

        // Then Redirect to Profile Edit Page
        return redirect()->route('home')->with('info', 'Your profile has been updated');
    }
}