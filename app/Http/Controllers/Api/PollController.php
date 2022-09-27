<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Choice;
use App\Models\Poll;
use App\Models\User;
use App\Models\Vote;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PollController extends Controller
{
    public function store(Request $request)
    {
        // return response()->json(['date' => new DateTime()]);
        if (auth()->user() === null) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validate = Validator::make($request->all(), [
            'title' => ['required'],
            'description' => ['required'],
            'deadline' => ['required'],
            'choices' => ['required'],
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Invalid input'], 422);
        }

        $poll = Poll::create([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'created_by' => auth()->user()->id
        ]);

        foreach ($request->choices as $choice) {
            $choice = Choice::create([
                'choice' => $choice,
                'poll_id' => $poll->id
            ]);
        }

        return response()->json($poll, 200);
    }

    public function index()
    {
        if (auth()->user() === null) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $polls = Poll::all();

        if (count($polls) === 0) {
            return response()->json(null, 404);
        }

        return response()->json($polls, 200);
    }

    public function show(Poll $poll)
    {
        if (auth()->user() === null) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $voted = Vote::where('poll_id', $poll->id)->where('user_id', auth()->user()->id)->first();
        if ($voted === null || auth()->user()->role !== 'admin') {
            return response()->json(null, 404);
        }

        $creator = User::findOrFail($poll->created_by);

        return response()->json([
            'data' => [
                'poll' => $poll,
                'creator' => $creator->username
            ]
        ], 200);
    }

    public function destroy(Poll $poll)
    {
        if (auth()->user() === null) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $poll->delete();
        return response()->json(['message' => 'Poll successfully deleted!'], 200);
    }

    public function vote(Poll $poll, Choice $choice)
    {
        if (auth()->user() === null) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $voted = Vote::where('poll_id', $poll->id)->where('user_id', auth()->user()->id)->first();
        if ($voted !== null) {
            return response()->json(['message' => 'already voted'], 422);
        }

        Vote::create([
            'choice_id' => $choice->id,
            'user_id' => auth()->user()->id,
            'poll_id' => $poll->id,
            'division_id' => auth()->user()->division->id
        ]);
        return response()->json([
            'message' => 'voting success'
        ], 200);
    }
}
