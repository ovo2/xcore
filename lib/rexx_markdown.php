<?php
class rexx_markdown extends Parsedown { 
	public function __construct($breaksEnabled = true) {
		$this->breaksEnabled = $breaksEnabled;
	}

	protected function blockFencedCodeComplete($Block) {
        $text = $Block['element']['text']['text'];

        $text = self::highlight($text, $Block['element']['text']['attributes']['class']);

        $Block['element']['text']['text'] = $text;

        return $Block;
	}

	// credits: http://php.net/manual/de/function.highlight-string.php#118550
	public static function highlight($text, $fileExt = '') {
		if (strpos($fileExt, 'lanuage-') === 0) {
			$fileExt = substr($fileExt, strlen('lanuage-'));
		}

		if ($fileExt == "php") {
		    ini_set("highlight.comment", "#008000");
		    ini_set("highlight.default", "#000000");
		    ini_set("highlight.html", "#808080");
		    ini_set("highlight.keyword", "#0000BB; font-weight: bold");
		    ini_set("highlight.string", "#DD0000");
		} else if ($fileExt == "html" || $fileExt == "javascript") {
		    ini_set("highlight.comment", "green");
		    ini_set("highlight.default", "#CC0000");
		    ini_set("highlight.html", "#000000");
		    ini_set("highlight.keyword", "black; font-weight: bold");
		    ini_set("highlight.string", "#0000FF");
		}

		$text = trim($text);
		$text = highlight_string("<?php " . $text, true);  // highlight_string() requires opening PHP tag or otherwise it will not colorize the text
		$text = trim($text);
		$text = preg_replace("|^\\<code\\>\\<span style\\=\"color\\: #[a-fA-F0-9]{0,6}\"\\>|", "", $text, 1);  // remove prefix
		$text = preg_replace("|\\</code\\>\$|", "", $text, 1);  // remove suffix 1
		$text = trim($text);  // remove line breaks
		$text = preg_replace("|\\</span\\>\$|", "", $text, 1);  // remove suffix 2
		$text = trim($text);  // remove line breaks
		$text = preg_replace("|^(\\<span style\\=\"color\\: #[a-fA-F0-9]{0,6}\"\\>)(&lt;\\?php&nbsp;)(.*?)(\\</span\\>)|", "\$1\$3\$4", $text);  // remove custom added "<?php "

		return $text;
	}
}
