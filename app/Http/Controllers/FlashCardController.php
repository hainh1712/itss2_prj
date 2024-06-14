<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;

class FlashCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = Carbon::now()->toDateString();
        $check = DB::table('practices') -> where('user_id', '=', Auth::id()) -> get();
        if($check->isNotEmpty() && $check[0]->day == $today){
            $randomCards = DB::table('cards') -> join('examples', 'examples.id', '=', 'cards.example_id')
                                              -> join('vocabularies', 'vocabularies.id', '=', 'examples.vocab_id')
                                              -> join('practices', 'practices.card_id', '=', 'cards.id')
                                              -> where('practices.user_id', '=', Auth::id())
                                              ->select('cards.*', 'examples.sentence as sentence', 'examples.vocab_id as vocab_id', 'vocabularies.name as name', 'vocabularies.meaning as meaning')
                                              -> get();
            return Inertia::render('User/FlashCard/FlashCardIndex', ['randomCards' => $randomCards]);
        }
        else if($check->isNotEmpty() && $check[0]->day != $today){
            DB::table('practices') -> where('user_id', '=', Auth::id()) -> delete();

            $tagAll = DB::table('tags') -> where('user_id', '=', Auth::id()) -> get();
            $cardAll = collect();
            foreach ($tagAll as $tag) {
                $cards = DB::table('cards') -> join('examples', 'examples.id', '=', 'cards.example_id')
                                            -> join('vocabularies', 'vocabularies.id', '=', 'examples.vocab_id')
                                            -> where('cards.tag_id', '=', $tag->id)
                                            -> where('cards.status', '!=', 2)
                                            ->select('cards.*', 'examples.sentence as sentence', 'examples.vocab_id as vocab_id', 'vocabularies.name as name', 'vocabularies.meaning as meaning')
                                            -> get();
                $cardAll = $cardAll->merge($cards);
            }
            $randomCards = $cardAll->count() >= 10 ? $cardAll->random(10) : $cardAll;
            // dd($randomCards);
            $this->storeRandomCardToPractice($randomCards, $today);
            return Inertia::render('User/FlashCard/FlashCardIndex', ['tagAll' => $tagAll, 'randomCards' => $randomCards]);
        }
        else {
            $tagAll = DB::table('tags') -> where('user_id', '=', Auth::id()) -> get();
            $cardAll = collect();
            foreach ($tagAll as $tag) {
                $cards = DB::table('cards') -> join('examples', 'examples.id', '=', 'cards.example_id')
                                            -> join('vocabularies', 'vocabularies.id', '=', 'examples.vocab_id')
                                            -> where('cards.tag_id', '=', $tag->id)
                                            -> where('cards.status', '!=', 2)
                                            ->select('cards.*', 'examples.sentence as sentence', 'examples.vocab_id as vocab_id', 'vocabularies.name as name', 'vocabularies.meaning as meaning')
                                            -> get();
                $cardAll = $cardAll->merge($cards);
            }
            $randomCards = $cardAll->count() >= 10 ? $cardAll->random(10) : $cardAll;
            // dd($randomCards);
            $this->storeRandomCardToPractice($randomCards, $today);
            return Inertia::render('User/FlashCard/FlashCardIndex', ['tagAll' => $tagAll, 'randomCards' => $randomCards]);
        }
    }

    public function storeRandomCardToPractice($randomCards, $today) {

        foreach ($randomCards as $card) {
            DB::table('practices') -> insert([
                'user_id' => Auth::id(),
                'card_id' => $card->id,
                'day' => $today
            ]);
        }
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function viewLearnFlashCard()
    {
        $randomCards = DB::table('cards') -> join('examples', 'examples.id', '=', 'cards.example_id')
                                              -> join('vocabularies', 'vocabularies.id', '=', 'examples.vocab_id')
                                              -> join('practices', 'practices.card_id', '=', 'cards.id')
                                              -> where('practices.user_id', '=', Auth::id())
                                              ->select('cards.*', 'examples.sentence as sentence', 'examples.vocab_id as vocab_id', 'vocabularies.name as name', 'vocabularies.meaning as meaning')
                                              -> get();
            return Inertia::render('User/FlashCard/FlashCardLearn', ['randomCards' => $randomCards]);
    }

    public function viewTest() {
        $tests = DB::table('tests') -> where('user_id', '=', Auth::id()) -> get();
        $tagAll = DB::table('tags') -> where('user_id', '=', Auth::id()) -> get();
            $cardAll = collect();
            foreach ($tagAll as $tag) {
                $cards = DB::table('cards') -> join('examples', 'examples.id', '=', 'cards.example_id')
                                            -> join('vocabularies', 'vocabularies.id', '=', 'examples.vocab_id')
                                            -> where('cards.tag_id', '=', $tag->id)
                                            -> where('cards.status', '=', 2)
                                            ->select('cards.*', 'examples.sentence as sentence', 'examples.vocab_id as vocab_id', 'vocabularies.name as name', 'vocabularies.meaning as meaning')
                                            -> get();
                $cardAll = $cardAll->merge($cards);
        }
        return Inertia::render('User/FlashCard/FlashCardTest', ['tests' => $tests, 'cardAll' => $cardAll]);
    }
}
