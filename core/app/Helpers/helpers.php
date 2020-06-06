<?php

//returns part of a text
if (! function_exists('the_excerpt')) {
    function the_excerpt($text, $length)
    {
        $full_text    = explode(' ', $text);
        $excerpt_text = implode(' ', array_splice($full_text, 0, $length));
        return $excerpt_text.'...';
    }
}