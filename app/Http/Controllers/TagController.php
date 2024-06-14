<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use App\Models\Card;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Inertia\Response;
use Illuminate\Support\Facades\Redirect;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $tagAll = DB::table('tags') -> where('user_id', '=', Auth::id()) -> get();
        // $data = [];
        foreach($tagAll as $tag){
            $response = $this->show($tag->id);
            // $responseData = $response->getData(true);
            // array_push($data, $responseData);
        }
        // $example = $data[0]['examples'];
        // dd($example);
        return Inertia::render('User/Tag/TagIndex', ['tags' => $tagAll]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $title = $request->input('title');
        $description = $request->input('description');
        DB::table('tags')->insert([
            'user_id' => Auth::id(),
            'title' => $title,
            'description' => $description,
            'status' => 1
        ]);
        return Redirect::back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tagAll = DB::table('tags') -> where('user_id', '=', Auth::id()) -> get();
        $cardAll = DB::table('cards') -> where('tag_id', '=', $id) ->get();
        $examples = [];
        $notLearn = 0;
        $learning = 0;
        $learned = 0;
        foreach($cardAll as $card) {
            if($card->status == 0){
                $learning++;
            }else if($card->status == 1){
                $learning++;
            }else if($card->status == 2){
                $learned++;
            }
            array_push($examples, DB::table('examples')
            ->join('vocabularies', 'vocabularies.id', '=', 'examples.vocab_id')
            ->join('cards', 'cards.example_id', '=', 'examples.id')
            ->select('examples.sentence', 'examples.meaning as ex_meaning', 'vocabularies.*', 'cards.status', 'cards.is_favorite')
            ->where('examples.id', $card->example_id)
            ->get());
        }
        // dd($examples);
        return Inertia::render('User/Tag/TagDetail', ['tags' => $tagAll, 'cards' => $cardAll, 'examples' => $examples, 'notLearn' => $notLearn, 'learning' => $learning, 'learned' => $learned, 'id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $title = $request->input('title');
        $description = $request->input('description');
        $id = $request->input('id');
        DB::table('tags') -> where('user_id', '=', Auth::id())
                          -> where('id', '=', $id)
                          -> update([
                            'title' => $title,
                            'description' => $description
                          ]);
        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id');
        DB::table('tags') -> where('user_id', '=', Auth::id())
                          -> where('id', '=', $id)
                          ->delete();
        return Redirect::route('tags.index');
    }
}
