--TEST--
Class rendering 002 - reference element with role="class" rendering
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xml_file = __DIR__ . "/data/class_rendering_002.xml";

$config->xml_file = $xml_file;

$format = new TestPHPChunkedXHTML($config, $outputHandler);
$render = new TestRender(new Reader($outputHandler), $config, $format);

$render->run();
?>
--EXPECTF--
Filename: classname.construct.html
Content:
<div id="classname.construct" class="refentry">
     <div class="refnamediv">
      <h1 class="refname">ClassName::__construct</h1>
      <p class="verinfo">(No version information available, might only be in Git)</p><p class="refpurpose"><span class="refname">ClassName::__construct</span> &mdash; <span class="dc-title">Returns new ClassName object</span></p>

     </div>

     <div class="refsect1 description" id="refsect1-classname.construct-description">
      Description
      <div class="constructorsynopsis dc-description"><span class="modifier">public</span> <span class="methodname"><strong>ClassName::__construct</strong></span>(<span class="methodparam"><span class="type"><a href="language.types.string.html" class="type string">string</a></span> <code class="parameter">$firstParameter</code><span class="initializer"> = &quot;now&quot;</span></span>)</div>

      <p class="para rdfs-comment">
       Returns new a ClassName object.
      </p>
     </div>


     <div class="refsect1 parameters" id="refsect1-classname.construct-parameters">
      <h3 class="title">Parameters</h3>
      <dl>
       
        <dt><code class="parameter">firstParameter</code></dt>
        <dd>
         <p class="para">
          The description of the parameter.
         </p>
        </dd>
       
      </dl>
     </div>


     <div class="refsect1 returnvalues" id="refsect1-classname.construct-returnvalues">
      <h3 class="title">Return Values</h3>
      <p class="para">
       Return values of the method.
      </p>
     </div>


     <div class="refsect1 errors" id="refsect1-classname.construct-errors">
      <h3 class="title">Errors/Exceptions</h3>
      <p class="para">
       Exceptions thrown and/or errors raised by the method.
      </p>
     </div>


    </div>
Filename: class.classname.html
Content:
<div id="class.classname" class="reference">

 <h1 class="title">The ClassName class</h1>
 

 <div class="partintro"><p class="verinfo">(No version information available, might only be in Git)</p>

  <div class="section" id="classname.intro">
   <h2 class="title">Introduction</h2>
   <p class="para">
    Introductory paragraph about the class.
   </p>
  </div>

  <div class="section" id="classname.synopsis">
   <h2 class="title">Class synopsis</h2>

   <div class="classsynopsis"><div class="classsynopsisinfo">
    
     <span class="modifier">class</span> <strong class="classname"><strong class="classname">ClassName</strong></strong>
    

    
     <span class="modifier">implements</span>
      <strong class="interfacename">InterfaceName</strong> {</div>

    <div class="classsynopsisinfo classsynopsisinfo_comment">/* Constants */</div>
    <div class="fieldsynopsis">
     <span class="modifier">public</span>
     <span class="modifier">const</span>
     <span class="type"><a href="language.types.string.html" class="type string">string</a></span>
      <var class="fieldsynopsis_varname"><a href=".html#classname.constants.first-constant"><var class="varname">FIRST_CONSTANT</var></a></var><span class="initializer"> = &quot;Initial value&quot;</span>;</div>


    <div class="classsynopsisinfo classsynopsisinfo_comment">/* Methods */</div>

    
   }</div>

  </div>

  <div class="section" id="classname.constants.types">
   <h2 class="title">Predefined Constants</h2>
   <dl>
    
     <dt id="classname.constants.first-constant"><strong><code>ClassName::FIRST_CONSTANT</code></strong></dt>
     <dd>
      <span class="simpara">
       The description of the class constant.
      </span>
     </dd>
    
   </dl>
  </div>

 </div>

</div>
