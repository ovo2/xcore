<?php

// ****************************************************************
// **  DELETE REDAXO CACHE AFTER YOU MADE CHANGES TO THIS FILE!  **
// ****************************************************************

// GLOBAL SPECIAL CHAR REWRITE
// used for rewriting special chars that are language dependent. valid for all languages
// separate values by | (pipe) symbol

$REX['ADDON']['seo42']['settings']['global_special_chars'] = 'ä|ö|ü|Ä|Ö|Ü|ß';
$REX['ADDON']['seo42']['settings']['global_special_chars_rewrite'] = 'ae|oe|ue|Ae|Oe|Ue|ss';

// LANG CODES
// used to determine the lang slugs of the url, like /de/foo.html
// if lang codes are not set in this array, $REX['CLANG'] will be used.

$REX['ADDON']['seo42']['settings']['langcodes'][0] = 'de';
//$REX['ADDON']['seo42']['settings']['langcodes'][1] = 'en';

// SPECIAL CHARS REWRITE
// used for rewriting special chars that are language dependent
// if no additional languages are defined in this array, array with clang = 0 will be used
// separate values by | (pipe) symbol

$REX['ADDON']['seo42']['settings']['special_chars'][0] = '&';
$REX['ADDON']['seo42']['settings']['special_chars_rewrite'][0] = 'und';

//$REX['ADDON']['seo42']['settings']['special_chars'][1] = '&';
//$REX['ADDON']['seo42']['settings']['special_chars_rewrite'][1] = 'and';

// ****************************************************************
// **  DELETE REDAXO CACHE AFTER YOU MADE CHANGES TO THIS FILE!  **
// ****************************************************************
