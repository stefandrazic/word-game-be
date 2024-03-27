<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WordController extends Controller
{
    public function score(Request $request)
    {
        $word = strtolower($request->input('word'));

        if (!$this->isValidWord($word)) {
            return response()->json(['error' => 'Invalid word'],);
        }

        $score = $this->calculateScore($word);

        if ($this->isPalindrome($word)) {
            $score += 3;
        } else if ($this->isAlmostPalindrome($word)) {
            $score += 2;
        }

        return response()->json(['score' => $score]);
    }

    private function isValidWord($word)
    {
        $response = Http::get("https://api.dictionaryapi.dev/api/v2/entries/en/$word");
        return $response->ok();
    }

    private function calculateScore($word)
    {
        $uniqueLetters = count(array_unique(str_split($word)));
        $score = $uniqueLetters;

        return $score;
    }

    private function isPalindrome($word)
    {
        return $word === strrev($word);
    }

    private function isAlmostPalindrome($word)
    {
        for ($i = 0; $i < strlen($word); $i++) {
            $tempWord = substr($word, 0, $i) . substr($word, $i + 1);
            if ($this->isPalindrome($tempWord)) {
                return true;
            }
        }
        return false;
    }
}
