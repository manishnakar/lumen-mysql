<?php

# app/Http/routes.php

use App\Models\Quote;

/**
 * Display the today quote
 */
$app->get('/', function() use ($app) {

    /*
     * Picks a different quote every day 
     * (for a maximum of 366 quotes)
     *
     *   - $count: the total number of available quotes
     *   - $day: the current day of the year (from 0 to 365)
     *   - $page: the page to look for to retrieve the 
     *            correct record
     */
    $count = Quote::query()->get()->count();
    $day = (int) date('z');
    $page = $day % $count + 1;

    $quotes = Quote::query()->get()->forPage($page, 1)->all();

    if (empty($quotes)) {
        throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
    }

    return view('quote', ['quote' => $quotes[0]]);
});

/**
 * Display a specific quote
 */
$app->get('/{id}', function($id) use ($app) {
    $quote = Quote::query()->findOrFail($id);
    return view('quote', ['quote' => $quote]);
});
