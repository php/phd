--TEST--
Note rendering - notes use div instead of blockquote, titles use h5, simpara uses p
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xmlFile = __DIR__ . "/data/note_rendering_001.xml";

$config->xmlFile = $xmlFile;

$format = new TestGenericChunkedXHTML($config, $outputHandler);
$render = new TestRender(new Reader($outputHandler), $config, $format);

$render->run();
?>
--EXPECT--
Filename: test_note.html
Content:
<div id="test_note" class="article">
<h1>Note rendering test</h1>

<div class="note"><strong class="note">Note</strong>
 <p class="simpara">Simple note content</p>
</div>

<div class="note"><strong class="note">Note</strong>
 <h5 class="title">Custom Title</h5>
 <p class="simpara">Note with a title</p>
</div>

</div>
