<?php

namespace Schoo\Http\Controllers;

use Alert;
use Auth;
use Cloudder;
use Illuminate\Http\Request;
use Redirect;
use Schoo\User;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('profile.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $input = $request->except('_token', 'url');

        if (User::where('username', '=', $request->get('username'))->exists()) {
            $input = $request->except('username', '_token', 'url');
            User::find(Auth::user()->id)->updateProfile($input);
            Alert::warning('Oops', 'Username Already Exists');

            return Redirect::back();
        }

        User::find(Auth::user()->id)->updateProfile($input);
        Alert::success('Good', 'You have successfully updated your profile');

        return redirect('/dashboard');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $img = $request->file('avatar');

        if (isset($img)) {
            Cloudder::upload($img);
            $imgurl = Cloudder::getResult()['url'];

            User::find(Auth::user()->id)->updateAvatar($imgurl);

            Alert::success('Good', 'Avatar updated successfully');

            return redirect('/courses');
        }
        Alert::warning('Oops', 'You need to select a file!');

        return Redirect::back();
    }
}
