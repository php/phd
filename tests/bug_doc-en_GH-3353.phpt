--TEST--
Bug doc-en GH-3353 - incorrect method/function linking when a method's name and a function's name are normalized to the same string
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/setup.php";

$xml_file = __DIR__ . "/data/bug_doc-en_GH-3353.xml";

$config->force_index = true;
$config->xml_file = $xml_file;

$render = new Render();

$indexRepository = new IndexRepository(new \SQLite3(":memory:"));
$indexRepository->init();
$config->indexcache = $indexRepository;


// Indexing
$index = new TestIndex($indexRepository, $config, $outputHandler);
$render->attach($index);

$reader = new Reader($outputHandler);
$reader->open($config->xml_file, null, LIBXML_PARSEHUGE | LIBXML_XINCLUDE);
$render->execute($reader);

$render->detach($index);


// Rendering
$format = new TestPHPChunkedXHTML($config, $outputHandler);
$render->attach($format);

$reader = new Reader($outputHandler);
$reader->open($config->xml_file, null, LIBXML_PARSEHUGE | LIBXML_XINCLUDE);

$render->execute($reader);
?>
--EXPECT--
Filename: method.and.function.html
Content:
<div id="method.and.function" class="refentry">
  <div class="refnamediv">
   <h1 class="refname">ClassName::methodName</h1>
   <h1 class="refname">classname_methodname</h1>
   <p class="verinfo">(No version information available, might only be in Git)</p><p class="refpurpose"><span class="refname">ClassName::methodName</span> -- <span class="refname">classname_methodname</span> &mdash; <span class="dc-title">1. This is the first method/function description</span></p>

  </div>
  <div class="refsect1 description" id="method.and.function-description">
   <p class="para">
    Link to <span class="function"><a href="method.only.html" class="function">ClassName::methodName()</a></span> with a function tag
    and to <span class="function"><a href="function.only.html" class="function">classname_methodname()</a></span> with a function tag
    and to <span class="methodname"><a href="method.only.html" class="methodname">ClassName::methodName()</a></span> with a methodname tag
    and to <span class="methodname"><a href="function.only.html" class="methodname">classname_methodname()</a></span> with a methodname tag
   </p>
  </div>

 </div>
Filename: method.only.html
Content:
<div id="method.only" class="refentry">
  <div class="refnamediv">
   <h1 class="refname">ClassName::methodName</h1>
   <p class="verinfo">(No version information available, might only be in Git)</p><p class="refpurpose"><span class="refname">ClassName::methodName</span> &mdash; <span class="dc-title">2. This is the second (method) description</span></p>

  </div>
  <div class="refsect1 description" id="method.only-description">
   <p class="para">
    Link to <span class="function"><strong>ClassName::methodName()</strong></span> with a function tag
    and to <span class="function"><a href="function.only.html" class="function">classname_methodname()</a></span> with a function tag
    and to <span class="methodname"><strong>ClassName::methodName()</strong></span> with a methodname tag
    and to <span class="methodname"><a href="function.only.html" class="methodname">classname_methodname()</a></span> with a methodname tag
   </p>
  </div>

 </div>
Filename: function.only.html
Content:
<div id="function.only" class="refentry">
  <div class="refnamediv">
   <h1 class="refname">classname_methodname</h1>
   <p class="verinfo">(No version information available, might only be in Git)</p><p class="refpurpose"><span class="refname">classname_methodname</span> &mdash; <span class="dc-title">3. This is the third (function) description</span></p>

  </div>
  <div class="refsect1 description" id="function.only-description">
   <p class="para">
    Link to <span class="function"><a href="method.only.html" class="function">ClassName::methodName()</a></span> with a function tag
    and to <span class="function"><strong>classname_methodname()</strong></span> with a function tag
    and to <span class="methodname"><a href="method.only.html" class="methodname">ClassName::methodName()</a></span> with a methodname tag
    and to <span class="methodname"><strong>classname_methodname()</strong></span> with a methodname tag
   </p>
  </div>

 </div>
Filename: function.only.html
Content:
<div id="bug_doc-en_GH-3353" class="refentry">
 

 

 
</div>
