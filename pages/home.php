<?php
// Functions
require_once 'lib/functions.php';

require_once 'Michelf/MarkdownExtra.inc.php';

// Get Markdown class
use Michelf\Markdown;
use Michelf\MarkdownExtra;

// Read file and pass content through the Markdown parser
$text = file_get_contents('../lib/markdown/homepage.md');
echo MarkdownExtra::defaultTransform($text);

LoadScripts();
?>