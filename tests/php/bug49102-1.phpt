--TEST--
Bug #49102 - Class reference pages don't normalize the methodnames in PhD trunk/
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../setup.php";
require_once __DIR__ . "/../TestRender.php";
require_once __DIR__ . "/TestChunkedXHTML.php";

$formatclass = "TestChunkedXHTML";
$xml_file = __DIR__ . "/data/bug49102-1.xml";

$opts = array(
    "index"             => true,
    "xml_root"          => dirname($xml_file),
    "xml_file"          => $xml_file,
    "output_dir"        => __DIR__ . "/output/",
    'language'          => 'en'
);

$extra = array(
    "lang_dir" => __DIR__ . "/../../phpdotnet/phd/data/langs/",
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
 

 <div class="partintro"><p class="verinfo">(No version information available, might only be in SVN)</p>

    <div class="section" id="splstack.synopsis">
        <h2 class="title">Class synopsis</h2>

    <div class="classsynopsis">
        <div class="ooclass"></div>

        <div class="classsynopsisinfo">
            <span class="ooclass">
                <b class="classname">SplStack</b>
            </span>

             <span class="ooclass">
                <span class="modifier">extends</span>
                <b class="classname">SplDoublyLinkedList</b>
            </span>

            <span class="oointerface">implements 
                <span class="interfacename"><b class="interfacename">Iterator</b></span>
            </span>

            <span class="oointerface">, 
                <span class="interfacename"><b class="interfacename">ArrayAccess</b></span>
            </span>

            <span class="oointerface">, 
                <span class="interfacename"><b class="interfacename">Countable</b></span>
            </span>
         {</div>

        <div class="classsynopsisinfo classsynopsisinfo_comment">/* Methods */</div>
        <div class="constructorsynopsis dc-description">
             <span class="methodname"><b>__construct</b></span>
             ( <span class="methodparam">void</span>
         )</div>

        <div class="methodsynopsis dc-description">
            <span class="type">void</span> <span class="methodname"><b>setIteratorMode</b></span>
             ( <span class="methodparam"><span class="type">int</span> <code class="parameter">$mode</code></span>
         )</div>


        <div class="classsynopsisinfo classsynopsisinfo_comment">/* Inherited methods */</div>
        <div class="methodsynopsis dc-description">
            <span class="type">mixed</span> <span class="methodname"><b>SplDoublyLinkedList::bottom</b></span>
             ( <span class="methodparam">void</span>
         )</div>

        <div class="methodsynopsis dc-description">
            <span class="type">int</span> <span class="methodname"><b>SplDoublyLinkedList::count</b></span>
             ( <span class="methodparam">void</span>
         )</div>

        <div class="methodsynopsis dc-description">
            <span class="type">mixed</span> <span class="methodname"><b>SplDoublyLinkedList::current</b></span>
             ( <span class="methodparam">void</span>
         )</div>

        <div class="methodsynopsis dc-description">
            <span class="type">int</span> <span class="methodname"><b>SplDoublyLinkedList::getIteratorMode</b></span>
             ( <span class="methodparam">void</span>
         )</div>

        <div class="methodsynopsis dc-description">
            <span class="type">bool</span> <span class="methodname"><b>SplDoublyLinkedList::offsetExists</b></span>
             ( <span class="methodparam"><span class="type"><a href="language.pseudo-types.html#language.types.mixed" class="type mixed">mixed</a></span> <code class="parameter">$index</code></span>
         )</div>

        <div class="methodsynopsis dc-description">
            <span class="type">mixed</span> <span class="methodname"><b>SplDoublyLinkedList::offsetGet</b></span>
             ( <span class="methodparam"><span class="type"><a href="language.pseudo-types.html#language.types.mixed" class="type mixed">mixed</a></span> <code class="parameter">$index</code></span>
         )</div>

    }</div>

    </div>
    </div>
</div>
