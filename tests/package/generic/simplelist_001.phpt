--TEST--
Simplelist rendering 001 - Types and columns
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xml_file = __DIR__ . "/data/simplelist.xml";

$config->setXml_file($xml_file);

$format = new TestGenericChunkedXHTML($config);
$render = new TestRender(new Reader, $config, $format);

$render->run();
?>
--EXPECTF--
Filename: simpelist.html
Content:
<div id="simpelist" class="chapter">

 <div class="section">
  <p class="para">1. Simplelist with no type</p>
  <ul class="simplelist">
   <li>First</li>
   <li>Second</li>
   <li>Third</li>
   <li>Fourth</li>
   <li>Fifth</li>
   <li>Sixth</li>
   <li>Seventh</li>
  </ul>
 </div>

 <div class="section">
  <p class="para">2. Simplelist with &quot;inline&quot; type</p>
  <span class="simplelist">First, Second, Third, Fourth, Fifth, Sixth, Seventh</span>
 </div>

 <div class="section">
  <p class="para">3. Simplelist with &quot;vert&quot; type, 3 columns</p>
  <table class="simplelist">
   <tbody>
    <tr>
     <td>First</td>
     <td>Fourth</td>
     <td>Seventh</td>
    </tr>
    <tr>
     <td>Second</td>
     <td>Fifth</td>
     <td></td>
    </tr>
    <tr>
     <td>Third</td>
     <td>Sixth</td>
     <td></td>
    </tr>
   </tbody>
  </table>
 </div>

 <div class="section">
  <p class="para">4. Simplelist with &quot;horiz&quot; type, 4 columns</p>
  <table class="simplelist">
   <tbody>
    <tr>
     <td>First</td>
     <td>Second</td>
     <td>Third</td>
     <td>Fourth</td>
    </tr>
    <tr>
     <td>Fifth</td>
     <td>Sixth</td>
     <td>Seventh</td>
     <td></td>
    </tr>
   </tbody>
  </table>
 </div>

 <div class="section">
  <p class="para">5. Simplelist with no type and various elements inside members</p>
  <ul class="simplelist">
   <li>1 <code class="literal">First</code></li>
   <li>2 <strong><code>Second</code></strong></li>
   <li>3 <code class="code">Third</code></li>
   <li>4 <code class="literal">Fourth</code></li>
   <li>5 <strong><code>Fifth</code></strong></li>
   <li>6 <code class="code">Sixth</code></li>
   <li>7 <code class="literal">Seventh</code></li>
  </ul>
 </div>

 <div class="section">
  <p class="para">6. Simplelist with &quot;inline&quot; type and various elements inside members</p>
  <span class="simplelist">1 <code class="literal">First</code>, 2 <strong><code>Second</code></strong>, 3 <code class="code">Third</code>, 4 <code class="literal">Fourth</code>, 5 <strong><code>Fifth</code></strong>, 6 <code class="code">Sixth</code>, 7 <code class="literal">Seventh</code></span>
 </div>

</div>
