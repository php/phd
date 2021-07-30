--TEST--
Bug #49102 - Class reference pages don't normalize the methodnames in PhD trunk/
--FILE--
<?php
namespace phpdotnet\phd\Tests\php;

use phpdotnet\phd\Tests\TestRender;

require_once __DIR__ . "/../setup.php";

$formatclass = TestChunkedXHTML::class;
$xml_file = __DIR__ . "/data/bug49102-1.xml";

$opts = array(
    "index"             => true,
    "xml_root"          => dirname($xml_file),
    "xml_file"          => $xml_file,
    "output_dir"        => __DIR__ . "/output/",
    'language'          => 'en'
);

$extra = array(
    "lang_dir" => __DIR__ . "/../../src/data/langs/",
    "phpweb_version_filename" => dirname($xml_file) . '/version.xml',
    "phpweb_acronym_filename" => dirname($xml_file) . '/acronyms.xml',
);

$test = new TestRender($formatclass, $opts, $extra);
$test->run();
?>

--EXPECTF--
Filename: class.splstack.html
Content:
<div id="class.splstack" class="reference">
 <h1 class="title">The SplStack class</h1>
 

 <div class="partintro"><p class="verinfo">(No version information available, might only be in Git)</p>

    <div class="section" id="splstack.synopsis">
        <h2 class="title">Class synopsis</h2>

    <div class="classsynopsis">
        <div class="ooclass"><strong class="classname"></strong></div>

        <div class="classsynopsisinfo">
            <span class="ooclass">
                <span class="modifier">class</span> <strong class="classname"><strong class="classname">SplStack</strong></strong>
            </span>

             <span class="ooclass">
                <span class="modifier">extends</span>
                <strong class="classname"> <strong class="classname">SplDoublyLinkedList</strong></strong>
            </span>

            <span class="oointerface"><span class="modifier">implements</span> 
                 <strong class="interfacename"><strong class="interfacename">Iterator</strong></strong></span><span class="oointerface">,  <strong class="interfacename"><strong class="interfacename">ArrayAccess</strong></strong></span><span class="oointerface">,  <strong class="interfacename"><strong class="interfacename">Countable</strong></strong></span> {</div>

        <div class="classsynopsisinfo classsynopsisinfo_comment">/* Methods */</div>
        <div class="constructorsynopsis dc-description">
            <span class="methodname"><strong>__construct</strong></span>()</div>

        <div class="methodsynopsis dc-description"><span class="methodname"><strong>setIteratorMode</strong></span>(<span class="methodparam"><span class="type">int</span> <code class="parameter">$mode</code></span>): <span class="type"><span class="type void">void</span></span></div>


        <div class="classsynopsisinfo classsynopsisinfo_comment">/* Inherited methods */</div>
        <div class="methodsynopsis dc-description"><span class="methodname"><strong>SplDoublyLinkedList::bottom</strong></span>(): <span class="type"><a href="language.types.declarations.html#language.types.declarations.mixed" class="type mixed">mixed</a></span></div>

        <div class="methodsynopsis dc-description"><span class="methodname"><strong>SplDoublyLinkedList::count</strong></span>(): <span class="type">int</span></div>

        <div class="methodsynopsis dc-description"><span class="methodname"><strong>SplDoublyLinkedList::current</strong></span>(): <span class="type"><a href="language.types.declarations.html#language.types.declarations.mixed" class="type mixed">mixed</a></span></div>

        <div class="methodsynopsis dc-description"><span class="methodname"><strong>SplDoublyLinkedList::getIteratorMode</strong></span>(): <span class="type">int</span></div>

        <div class="methodsynopsis dc-description"><span class="methodname"><strong>SplDoublyLinkedList::offsetExists</strong></span>(<span class="methodparam"><span class="type"><a href="language.types.declarations.html#language.types.declarations.mixed" class="type mixed">mixed</a></span> <code class="parameter">$index</code></span>): <span class="type">bool</span></div>

        <div class="methodsynopsis dc-description"><span class="methodname"><strong>SplDoublyLinkedList::offsetGet</strong></span>(<span class="methodparam"><span class="type"><a href="language.types.declarations.html#language.types.declarations.mixed" class="type mixed">mixed</a></span> <code class="parameter">$index</code></span>): <span class="type"><a href="language.types.declarations.html#language.types.declarations.mixed" class="type mixed">mixed</a></span></div>

    }</div>

    </div>
    </div>
</div>
