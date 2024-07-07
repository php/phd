--TEST--
Bug #49102 - Class reference pages don't normalize the methodnames in PhD trunk/
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xml_file = __DIR__ . "/data/bug49102-1.xml";

$config->setXml_file($xml_file);

$format = new TestPHPChunkedXHTML($config);
$render = new TestRender(new Reader, $config, $format);

$render->run();
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
        <span class="ooclass"><strong class="classname"></strong></span>

        <div class="classsynopsisinfo">
            <span class="ooclass">
                <span class="modifier">class</span> <strong class="classname">SplStack</strong>
            </span>

             <span class="ooclass">
                <span class="modifier">extends</span>
                 <strong class="classname">SplDoublyLinkedList</strong>
            </span>

            <span class="oointerface"><span class="modifier">implements</span> 
                 <strong class="interfacename">Iterator</strong></span><span class="oointerface">,  <strong class="interfacename">ArrayAccess</strong></span><span class="oointerface">,  <strong class="interfacename">Countable</strong></span> {</div>

        <div class="classsynopsisinfo classsynopsisinfo_comment">/* Methods */</div>
        <div class="constructorsynopsis dc-description">
            <span class="methodname"><strong>__construct</strong></span>()</div>

        <div class="methodsynopsis dc-description"><span class="methodname"><strong>setIteratorMode</strong></span>(<span class="methodparam"><span class="type"><a href="language.types.integer.html" class="type int">int</a></span> <code class="parameter">$mode</code></span>): <span class="type"><a href="language.types.void.html" class="type void">void</a></span></div>


        <div class="classsynopsisinfo classsynopsisinfo_comment">/* Inherited methods */</div>
        <div class="methodsynopsis dc-description"><span class="methodname"><strong>SplDoublyLinkedList::bottom</strong></span>(): <span class="type"><a href="language.types.mixed.html" class="type mixed">mixed</a></span></div>

        <div class="methodsynopsis dc-description"><span class="methodname"><strong>SplDoublyLinkedList::count</strong></span>(): <span class="type"><a href="language.types.integer.html" class="type int">int</a></span></div>

        <div class="methodsynopsis dc-description"><span class="methodname"><strong>SplDoublyLinkedList::current</strong></span>(): <span class="type"><a href="language.types.mixed.html" class="type mixed">mixed</a></span></div>

        <div class="methodsynopsis dc-description"><span class="methodname"><strong>SplDoublyLinkedList::getIteratorMode</strong></span>(): <span class="type"><a href="language.types.integer.html" class="type int">int</a></span></div>

        <div class="methodsynopsis dc-description"><span class="methodname"><strong>SplDoublyLinkedList::offsetExists</strong></span>(<span class="methodparam"><span class="type"><a href="language.types.mixed.html" class="type mixed">mixed</a></span> <code class="parameter">$index</code></span>): <span class="type"><a href="language.types.boolean.html" class="type bool">bool</a></span></div>

        <div class="methodsynopsis dc-description"><span class="methodname"><strong>SplDoublyLinkedList::offsetGet</strong></span>(<span class="methodparam"><span class="type"><a href="language.types.mixed.html" class="type mixed">mixed</a></span> <code class="parameter">$index</code></span>): <span class="type"><a href="language.types.mixed.html" class="type mixed">mixed</a></span></div>

    }</div>

    </div>
    </div>
</div>


