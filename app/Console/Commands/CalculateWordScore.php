<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CalculateWordScore extends Command
{

    //  The name and signature of the console command.

    protected $signature = 'word:score {word}';

    protected $description = 'Calculate the score for a given word';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $word = strtolower($this->argument('word'));
        if (!$this->isValidWord($word)) {
            $this->error('Invalid word');
            return;
        }

        $score = $this->calculateScore($word);

        if ($this->isPalindrome($word)) {
            $score += 3;
        } else if ($this->isAlmostPalindrome($word)) {
            $score += 2;
        }

        $this->info("Score for '$word': $score");
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
