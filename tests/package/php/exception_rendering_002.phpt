--TEST--
Exception rendering 002 - reference element with role="exception" rendering
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xml_file = __DIR__ . "/data/exception_rendering_002.xml";

$config->setXml_file($xml_file);

$format = new TestPHPChunkedXHTML;
$render = new TestRender(new Reader, $config, $format);

$render->run();
?>
--EXPECTF--
Filename: exceptionname.construct.html
Content:
<div id="exceptionname.construct" class="refentry">
      <div class="refnamediv">
       <h1 class="refname">ExceptionName::__construct</h1>
       <p class="verinfo">(No version information available, might only be in Git)</p><p class="refpurpose"><span class="refname">ExceptionName::__construct</span> &mdash; <span class="dc-title">Constructs the exception</span></p>

      </div>

      <div class="refsect1 description" id="refsect1-exceptionname.construct-description">
       Description
       <div class="constructorsynopsis dc-description"><span class="modifier">public</span> <span class="methodname"><strong>ExceptionName::__construct</strong></span>(<span class="methodparam"><span class="type"><a href="language.types.string.html" class="type string">string</a></span> <code class="parameter">$propertyName</code><span class="initializer"> = &quot;&quot;</span></span>)</div>

       <p class="para rdfs-comment">
        Constructs the Exception.
       </p>
      </div>


      <div class="refsect1 parameters" id="refsect1-exceptionname.construct-parameters">
       Parameters
       <p class="para">
       <dl>
        
         <dt><code class="parameter">parameterName</code></dt>
         <dd>
          <p class="para">
           Description of the parameter
          </p>
         </dd>
        
       </dl>
      </p>
     </div>

    </div>
Filename: class.exceptionname.html
Content:
<div id="class.exceptionname" class="reference">
 <h1 class="title">The ExceptionName Exception</h1>
 

 <div class="partintro"><p class="verinfo">(No version information available, might only be in Git)</p>

  <div class="section" id="exceptionname.intro">
   Introduction
   <p class="para">
    An ExceptionName exception.
   </p>
  </div>

  <div class="section" id="exceptionname.synopsis">
   Class synopsis

   <div class="classsynopsis"><div class="classsynopsisinfo">
    
     <span class="modifier">class</span> <strong class="classname"><strong class="exceptionname">ExceptionName</strong></strong>
    

    
     <span class="modifier">extends</span>
      <strong class="classname">Exception</strong>
     {</div>

    <div class="classsynopsisinfo classsynopsisinfo_comment">/* Properties */</div>
    <div class="fieldsynopsis">
     <span class="modifier">protected</span>
     <span class="type"><a href="language.types.integer.html" class="type int">int</a></span>
      <var class="varname"><a href=".html#exceptionname.props.propertyname">$<var class="varname">propertyName</var></a></var><span class="initializer"> = &quot;Initial name&quot;</span>;</div>


    <div class="classsynopsisinfo classsynopsisinfo_comment">/* Methods */</div>
     
   }</div>

  </div>

  <div class="section" id="exceptionname.props">
   Properties
   <dl>
    
     <dt id="exceptionname.props.propertyname"><var class="varname">propertyName</var></dt>
     <dd>
      <p class="para">Description of the property</p>
     </dd>
    
   </dl>
  </div>

 </div>

</div>
