<?php

if(!function_exists('content_part')) {
    function content_part($location) {
        $part = \Titantwentyone\FilamentCMS\Models\Part::where('location', $location)->first();
        echo $part?->render();
    }
}
