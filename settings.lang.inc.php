<?php

// ****************************************************************
// **  DELETE REDAXO CACHE AFTER YOU MADE CHANGES TO THIS FILE!  **
// ****************************************************************


// LANG CODES
// used to determine the lang slugs of the url, like /de/foo.html
// hint: if lang codes are not set in this array, $REX['CLANG'] will be used.

$REX['ADDON']['rexseo42']['settings']['langcodes'][0] = 'de';
//$REX['ADDON']['rexseo42']['settings']['langcodes'][1] = 'en';


// SPECIAL CHARS REWRITE
// used for rewriting special chars that are language dependent
// hint: if no additional languages are defined in this array, array with clang = 0 will be used

$REX['ADDON']['rexseo42']['settings']['special_chars'][0] = 'ä|ö|ü|Ä|Ö|Ü|ß|&';
$REX['ADDON']['rexseo42']['settings']['special_chars_rewrite'][0] = 'ae|oe|ue|Ae|Oe|Ue|ss|und';

//$REX['ADDON']['rexseo42']['settings']['special_chars'][1] = 'ä|ö|ü|Ä|Ö|Ü|ß|&';
//$REX['ADDON']['rexseo42']['settings']['special_chars_rewrite'][1] = 'ae|oe|ue|Ae|Oe|Ue|ss|and';

