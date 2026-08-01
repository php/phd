--TEST--
GH-80: Be more lax in function/methodnames tag in regards to whitespace
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../setup.php";

$xml_file = __DIR__ . "/data/bug_GH-80.xml";

$config->setXml_file($xml_file);
$config->setForce_index(true);

$render = new Render();

$indexRepository = new IndexRepository(new \SQLite3(":memory:"));
$indexRepository->init();
$config->set_indexcache($indexRepository);

$index = new TestIndex($indexRepository, $config);
$render->attach($index);

$reader = new Reader;
$reader->open($config->xml_file(), null, LIBXML_PARSEHUGE | LIBXML_XINCLUDE);
$render->execute($reader);

$render->detach($index);

$format = new TestPHPChunkedXHTML($config);
$render = new TestRender(new Reader, $config, $format);

$render->run();
?>
--EXPECT--
Filename: test-function.html
Content:
<div id="test-function" class="refentry">
  <div class="refnamediv">
   <h1 class="refname">test_function</h1>
  </div>
  <p class="verinfo">(No version information available, might only be in Git)</p><p class="refpurpose"><span class="refname">test_function</span> &mdash; <span class="dc-title">For testing</span></p>

 </div>
Filename: example.test-method.html
Content:
<div id="example.test-method" class="refentry">
   <div class="refnamediv">
    <h1 class="refname">Example::testMethod</h1>
    <p class="verinfo">(No version information available, might only be in Git)</p><p class="refpurpose"><span class="refname">Example::testMethod</span> &mdash; <span class="dc-title">An example method for testing</span></p>

   </div>
  </div>
Filename: test-class.html
Content:
<div id="test-class" class="reference">
  
  <div class="classsynopsis"><div class="classsynopsisinfo">
   
    <span class="modifier">class</span> <strong class="classname"><strong class="classname">Example</strong></strong>
    {</div>
  }</div>
  
 <h2>Table of Contents</h2><ul class="chunklist chunklist_reference"><li><a href="example.test-method.html">Example::testMethod</a> — An example method for testing</li></ul>
</div>

Filename: test-variable.html
Content:
<div id="test-variable" class="refentry">
  <div class="refnamediv">
   <h1 class="refname">$TEST_VARIABLE</h1>
   <p class="verinfo">(No version information available, might only be in Git)</p><p class="refpurpose"><span class="refname">$TEST_VARIABLE</span> &mdash; <span class="dc-title">A test variable</span></p>

  </div>
 </div>
Filename: test-references.html
Content:
<div id="test-references" class="refentry">
  <div class="refsect1 unknown-1" id="refsect1-test-references-unknown-1">
   <p class="para">
    <span class="function"><a href="test-function.html" class="function"> test_function()</a></span> should be linked.
   </p>
   <p class="para">
    <span class="classname"><a href="test-class.html" class="classname"> Example</a></span> should be linked.
   </p>
   <p class="para">
    <span class="methodname"><a href="example.test-method.html" class="methodname"> Example::testMethod()</a></span> should be linked.
   </p>
   <p class="para">
    <var class="varname"><a href="test-variable.html" class="classname"> $TEST_VARIABLE</a></var> should be linked.
   </p>
  </div>

 </div>
Filename: test-references.html
Content:
<div id="bug_GH-80" class="refentry">
 

 

 

 
</div>
