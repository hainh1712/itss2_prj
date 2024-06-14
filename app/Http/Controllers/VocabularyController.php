<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVocabularyRequest;
use App\Http\Requests\UpdateVocabularyRequest;
use Illuminate\Http\Request;
use App\Models\Vocabulary;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Redirect;

class VocabularyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        // Add Vocabulary
        $dataTagId = $request->input('tagid');
        $valueTagId = $dataTagId['_value'];
        $dataVocab = $request->input('newVocab');
        $valueVocab = $dataVocab['_value'];
        $vocab = strtolower($valueVocab);
        if((DB::table('vocabularies') -> where('name', '=', $vocab) -> get())->isEmpty()){
            $dataMeaning = $request->input('newMeaning');
            $valueMeaning = $dataMeaning['_value'];
            $id = DB::table('vocabularies') -> insertGetId([
                'name' => $vocab,
                'meaning' => $valueMeaning
            ]);

            // Add Example
            $dataExample = $request->input('newExample');
            $valueExample = $dataExample['_value'];
            $dataExampleMeaning = $request->input('newExampleMeaning');
            $valueExampleMeaning = $dataExampleMeaning['_value'];
            $idExample = DB::table('examples') -> insertGetId([
                'vocab_id' => $id,
                'sentence' => $valueExample,
                'meaning' => $valueExampleMeaning
            ]);

            //Add Card
            DB::table('cards') -> insert([
                'tag_id' => $valueTagId,
                'example_id' => $idExample,
                'status' => 0,
                'is_favorite' => 0
            ]);

            return Redirect::back();
        }else{
            return Redirect::back();
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Vocabulary $vocabulary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vocabulary $vocabulary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVocabularyRequest $request, Vocabulary $vocabulary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vocabulary $vocabulary)
    {
        //
    }

    public function searchIndex()
    {
        return Inertia::render('User/Vocabulary/Search');
    }
    public function searchDetail(String $vocab)
    {
        $vocabularySearch = strtolower($vocab);
        $vocabulary = DB::table('vocabularies') -> where('name', '=', $vocabularySearch) ->get();
        if($vocabulary->isEmpty()){
            return Redirect::back() -> with(['message' => 'SearchFailed']);
        }else {
            $examples = DB::table('examples') -> where('vocab_id', '=', $vocabulary[0]->id) -> get();
            return Inertia::render('User/Vocabulary/SearchDetail', ['vocab' => $vocab, 'examples' => $examples, 'vocabulary' => $vocabulary]);
        }
    }
}
