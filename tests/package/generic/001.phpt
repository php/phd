--TEST--
CALS Table rendering
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xml_file = __DIR__ . "/data/001-1.xml";

$config->setXml_file($xml_file);

$format = new TestGenericChunkedXHTML($config, $outputHandler);
$render = new TestRender(new Reader($outputHandler), $config, $format);

$render->run();
?>
--EXPECT--
Filename: test_article.html
Content:
<div id="test_article" class="article">
<h1>Example table</h1>

<table id="ex.calstable" class="doctable table">
<caption><strong>Sample CALS Table</strong></caption>

<col style="text-align: left;" />
<col style="text-align: left;" />
<col style="text-align: left;" />
<col style="text-align: left;" />
<thead>
<tr>
  <th colspan="2">Horizontal Span</th>
  <th>a3</th>
  <th>a4</th>
  <th>a5</th>
</tr>

</thead>

<tfoot>
<tr>
  <th>f1</th>
  <th>f2</th>
  <th>f3</th>
  <th>f4</th>
  <th>f5</th>
</tr>

</tfoot>

<tbody class="tbody">
<tr>
  <td style="text-align: left;">b1</td>
  <td style="text-align: left;">b2</td>
  <td style="text-align: left;">b3</td>
  <td style="text-align: left;">b4</td>
  <td rowspan="2" style="text-align: left; vertical-align: middle;"><p class="para">Vertical Span</p></td>
</tr>

<tr>
  <td style="text-align: left;">c1</td>
  <td colspan="2" rowspan="2" style="text-align: center; vertical-align: bottom;">Span Both</td>
  <td style="text-align: left;">c4</td>
</tr>

<tr>
  <td style="text-align: left;">d1</td>
  <td style="text-align: left;">d4</td>
  <td style="text-align: left;">d5</td>
</tr>

</tbody>

</table>


</div>
