--TEST--
Type rendering 003 - Constructorsynopsis parameters and parameter types
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../setup.php";
require_once __DIR__ . "/TestChunkedXHTML.php";

$formatclass = "TestChunkedXHTML";
$xml_file = __DIR__ . "/data/type_rendering_constructorsynopsis_parameters-and-return-type.xml";

$opts = array(
    "index"             => true,
    "xml_root"          => dirname($xml_file),
    "xml_file"          => $xml_file,
    "output_dir"        => __DIR__ . "/output/",
);

$extra = array(
    "lang_dir" => __PHDDIR__ . "phpdotnet/phd/data/langs/",
    "phpweb_version_filename" => dirname($xml_file) . '/version.xml',
    "phpweb_acronym_filename" => dirname($xml_file) . '/acronyms.xml',
);

$render = new TestRender($formatclass, $opts, $extra);

if (Index::requireIndexing() && !file_exists($opts["output_dir"])) {
    mkdir($opts["output_dir"], 0755);
}

$render->run();
?>
--EXPECT--
Filename: type-rendering-constructorsynopsis-parameters-and-return-type.html
Content:
<div id="type-rendering-constructorsynopsis-parameters-and-return-type" class="chapter">

 <div class="section">
  <p class="para">1. Constructor with no parameters, no return type</p>
  <div class="constructorsynopsis dc-description"><span class="modifier">final</span> <span class="modifier">public</span> <span class="methodname"><strong>ClassName::__construct</strong></span>()</div>

 </div>

 <div class="section">
  <p class="para">2. Constructor with no parameters, void (element) return type</p>
  <div class="constructorsynopsis dc-description"><span class="modifier">final</span> <span class="modifier">public</span> <span class="methodname"><strong>ClassName::__construct</strong></span>(): <span class="type"><a href="language.types.void.html" class="type void">void</a></span></div>

 </div>

 <div class="section">
  <p class="para">3. Constructor with one parameter</p>
  <div class="constructorsynopsis dc-description"><span class="modifier">final</span> <span class="modifier">private</span> <span class="methodname"><strong>ClassName::__construct</strong></span>(<span class="methodparam"><span class="type"><a href="language.types.mixed.html" class="type mixed">mixed</a></span> <code class="parameter">$anything</code></span>)</div>

 </div>

 <div class="section">
  <p class="para">4. Constructor with optional parameter</p>
  <div class="constructorsynopsis dc-description"><span class="modifier">final</span> <span class="modifier">protected</span> <span class="methodname"><strong>ClassName::__construct</strong></span>(<span class="methodparam"><span class="type"><a href="language.types.integer.html" class="type int">int</a></span> <code class="parameter">$count</code><span class="initializer"> = 0</span></span>)</div>

 </div>

 <div class="section">
  <p class="para">5. Constructor with nullable parameter</p>
  <div class="constructorsynopsis dc-description"><span class="modifier">final</span> <span class="modifier">public</span> <span class="methodname"><strong>ClassName::__construct</strong></span>(<span class="methodparam"><span class="type"><span class="type"><a href="language.types.null.html" class="type null">?</a></span><span class="type"><a href="language.types.float.html" class="type float">float</a></span></span> <code class="parameter">$value</code></span>)</div>

 </div>

 <div class="section">
  <p class="para">6. Constructor with nullable optional parameter</p>
  <div class="constructorsynopsis dc-description"><span class="modifier">final</span> <span class="modifier">private</span> <span class="methodname"><strong>ClassName::__construct</strong></span>(<span class="methodparam"><span class="type"><span class="type"><a href="language.types.null.html" class="type null">?</a></span><span class="type"><a href="language.types.string.html" class="type string">string</a></span></span> <code class="parameter">$options</code><span class="initializer"> = &quot;&quot;</span></span>)</div>

 </div>

 <div class="section">
  <p class="para">7. Constructor with reference parameter</p>
  <div class="constructorsynopsis dc-description"><span class="modifier">final</span> <span class="modifier">protected</span> <span class="methodname"><strong>ClassName::__construct</strong></span>(<span class="methodparam"><span class="type"><a href="language.types.array.html" class="type array">array</a></span> <code class="parameter reference">&$reference</code></span>)</div>

 </div>

 <div class="section">
  <p class="para">8. Constructor with union type parameter</p>
  <div class="constructorsynopsis dc-description"><span class="modifier">final</span> <span class="modifier">public</span> <span class="methodname"><strong>ClassName::__construct</strong></span>(<span class="methodparam"><span class="type"><span class="type"><a href="language.types.iterable.html" class="type iterable">iterable</a></span>|<span class="type"><a href="language.types.resource.html" class="type resource">resource</a></span>|<span class="type"><a href="language.types.callable.html" class="type callable">callable</a></span>|<span class="type"><a href="language.types.null.html" class="type null">null</a></span></span> <code class="parameter">$option</code></span>)</div>

 </div>

 <div class="section">
  <p class="para">9. Constructor with intersection type parameter</p>
  <div class="constructorsynopsis dc-description"><span class="modifier">final</span> <span class="modifier">public</span> <span class="methodname"><strong>ClassName::__construct</strong></span>(<span class="methodparam"><span class="type"><span class="type">Countable</span>&amp;<span class="type">Traversable</span></span> <code class="parameter">$option</code></span>)</div>

 </div>

 <div class="section">
  <p class="para">10. Constructor with DNF (Disjunctive Normal Form) type parameter</p>
  <div class="constructorsynopsis dc-description"><span class="modifier">final</span> <span class="modifier">public</span> <span class="methodname"><strong>ClassName::__construct</strong></span>(<span class="methodparam"><span class="type">(<span class="type">Countable</span>&amp;<span class="type">Traversable</span>)|<span class="type">DOMAttr</span></span> <code class="parameter">$option</code></span>)</div>

 </div>

 <div class="section">
  <p class="para">11. Constructor with more than three parameters</p>
  <div class="constructorsynopsis dc-description"><span class="modifier">final</span> <span class="modifier">private</span> <span class="methodname"><strong>ClassName::__construct</strong></span>(<br>&nbsp;&nbsp;&nbsp;&nbsp;<span class="methodparam"><span class="type"><a href="language.types.integer.html" class="type int">int</a></span> <code class="parameter">$count</code></span>,<br>&nbsp;&nbsp;&nbsp;&nbsp;<span class="methodparam"><span class="type"><a href="language.types.string.html" class="type string">string</a></span> <code class="parameter">$name</code></span>,<br>&nbsp;&nbsp;&nbsp;&nbsp;<span class="methodparam"><span class="type"><a href="language.types.boolean.html" class="type bool">bool</a></span> <code class="parameter">$isSomething</code></span>,<br>&nbsp;&nbsp;&nbsp;&nbsp;<span class="methodparam"><span class="type"><a href="language.types.array.html" class="type array">array</a></span> <code class="parameter">$list</code></span><br>)</div>

 </div>

</div>
