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
%w<h1 class="title">The SplStack class</h1>
%w
%w 
%w<div class="partintro">

%w<div id="splstack.synopsis" class="section">
%w<h2 class="title">Class synopsis</h2> 

%w<div class="classsynopsis">
%w<div class="ooclass"><b class="classname">SplStack</b></div>
 


%w<div class="classsynopsisinfo">
%w<span class="ooclass">
%w<b class="classname">SplStack</b>
%w</span>

%w<span class="ooclass">
%w<span class="modifier">extends</span>
%w<a href="class.spldoublylinkedlist.html" class="classname">SplDoublyLinkedList</a>
%w</span>

%w<span class="oointerface">implements 
%w<a href="class.iterator.html" class="interfacename">Iterator</a>
%w</span>

%w<span class="oointerface">, 
%w<a href="class.arrayaccess.html" class="interfacename">ArrayAccess</a>
%w</span>
 
%w<span class="oointerface">, 
%w<a href="class.countable.html" class="interfacename">Countable</a>
%w</span>
 
%w{</div>

 
    
 
%w<div class="classsynopsisinfo classsynopsisinfo_comment">/* Methods */</div>
%w<div class="constructorsynopsis dc-description">
%w<span class="methodname"><a href="splstack.construct.html" class="function">__construct</a></span>
%w( <span class="methodparam">void</span>
%w)</div>

%w<div class="methodsynopsis dc-description">
%w<span class="type">void</span> <span class="methodname"><a href="splstack.setiteratormode.html" class="function">setIteratorMode</a></span>
%w( <span class="methodparam"><span class="type">int</span> <tt class="parameter">$mode</tt></span>
%w)</div>


%w<div class="classsynopsisinfo classsynopsisinfo_comment">/* Inherited methods */</div>
%w<div class="methodsynopsis dc-description">
%w<span class="type">mixed</span> <span class="methodname"><a href="spldoublylinkedlist.bottom.html" class="function">SplDoublyLinkedList::bottom</a></span>
%w( <span class="methodparam">void</span>
%w)</div>
%w<div class="methodsynopsis dc-description">
%w<span class="type">int</span> <span class="methodname"><a href="spldoublylinkedlist.count.html" class="function">SplDoublyLinkedList::count</a></span>
%w( <span class="methodparam">void</span>
%w)</div>
%w<div class="methodsynopsis dc-description">
%w<span class="type">mixed</span> <span class="methodname"><a href="spldoublylinkedlist.current.html" class="function">SplDoublyLinkedList::current</a></span>
%w( <span class="methodparam">void</span>
%w)</div>
%w<div class="methodsynopsis dc-description">
%w<span class="type">int</span> <span class="methodname"><a href="spldoublylinkedlist.getiteratormode.html" class="function">SplDoublyLinkedList::getIteratorMode</a></span>
%w( <span class="methodparam">void</span>
%w)</div>
%w<div class="methodsynopsis dc-description">
%w<span class="type">bool</span> <span class="methodname"><a href="spldoublylinkedlist.offsetexists.html" class="function">SplDoublyLinkedList::offsetExists</a></span>
%w( <span class="methodparam"><span class="type"><a href="language.pseudo-types.html#language.types.mixed" class="type mixed">mixed</a></span> <tt class="parameter">$index</tt></span>
%w)</div>
<div class="methodsynopsis dc-description">
%w<span class="type">mixed</span> <span class="methodname"><a href="spldoublylinkedlist.offsetget.html" class="function">SplDoublyLinkedList::offsetGet</a></span>
%w( <span class="methodparam"><span class="type"><a href="language.pseudo-types.html#language.types.mixed" class="type mixed">mixed</a></span> <tt class="parameter">$index</tt></span>
%w)</div>

%w}</div>
 
%w</div>
%w</div>
%w</div>
