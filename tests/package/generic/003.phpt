--TEST--
CALS Table rendering#003
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../setup.php";

$xml_file = __DIR__ . "/data/003.xml";

$config->xml_file = $xml_file;

$format = new TestGenericChunkedXHTML($config, $outputHandler);
$render = new TestRender(new Reader($outputHandler), $config, $format);

$render->run();
?>
--EXPECT--
Filename: function.nl-langinfo.html
Content:
<div id="function.nl-langinfo" class="article">
 <table class="doctable table">
  <caption><strong>nl_langinfo Constants</strong></caption>
  
   <col />
   <col />
   <thead>
    <tr>
     <th>Constant</th>
     <th>Description</th>
    </tr>

   </thead>

   <tbody class="tbody">
    <tr>
     <td colspan="2" style="text-align: center;"><em>LC_TIME Category Constants</em></td>
    </tr>

    <tr>
     <td>ABDAY_(1-7)</td>
     <td>Abbreviated name of n-th day of the week.</td>
    </tr>

    <tr>
     <td>DAY_(1-7)</td>
     <td>Name of the n-th day of the week (DAY_1 = Sunday).</td>
    </tr>

    <tr>
     <td>ABMON_(1-12)</td>
     <td>Abbreviated name of the n-th month of the year.</td>
    </tr>

    <tr>
     <td>MON_(1-12)</td>
     <td>Name of the n-th month of the year.</td>
    </tr>

    <tr>
     <td>AM_STR</td>
     <td>String for Ante meridian.</td>
    </tr>

    <tr>
     <td>PM_STR</td>
     <td>String for Post meridian.</td>
    </tr>

    <tr>
     <td>D_T_FMT</td>
     <td>String that can be used as the format string for <span class="function">strftime</span> to represent time and date.</td>
    </tr>

    <tr>
     <td>D_FMT</td>
     <td>String that can be used as the format string for <span class="function">strftime</span> to represent date.</td>
    </tr>

    <tr>
     <td>T_FMT</td>
     <td>String that can be used as the format string for <span class="function">strftime</span> to represent time.</td>
    </tr>

    <tr>
     <td>T_FMT_AMPM</td>
     <td>String that can be used as the format string for <span class="function">strftime</span> to represent time in 12-hour format with ante/post meridian.</td>
    </tr>

    <tr>
     <td>ERA</td>
     <td>Alternate era.</td>
    </tr>

    <tr>
     <td>ERA_YEAR</td>
     <td>Year in alternate era format.</td>
    </tr>

    <tr>
     <td>ERA_D_T_FMT</td>
     <td>Date and time in alternate era format (string can be used in <span class="function">strftime</span>).</td>
    </tr>

    <tr>
     <td>ERA_D_FMT</td>
     <td>Date in alternate era format (string can be used in <span class="function">strftime</span>).</td>
    </tr>

    <tr>
     <td>ERA_T_FMT</td>
     <td>Time in alternate era format (string can be used in <span class="function">strftime</span>).</td>
    </tr>

    <tr>
     <td colspan="2" style="text-align: center;"><em>LC_MONETARY Category Constants</em></td>
    </tr>

    <tr>
     <td>INT_CURR_SYMBOL</td>
     <td>International currency symbol.</td>
    </tr>

    <tr>
     <td>CURRENCY_SYMBOL</td>
     <td>Local currency symbol.</td>
    </tr>

    <tr>
     <td>CRNCYSTR</td>
     <td>Same value as CURRENCY_SYMBOL.</td>
    </tr>

    <tr>
     <td>MON_DECIMAL_POINT</td>
     <td>Decimal point character.</td>
    </tr>

    <tr>
     <td>MON_THOUSANDS_SEP</td>
     <td>Thousands separator (groups of three digits).</td>
    </tr>

    <tr>
     <td>MON_GROUPING</td>
     <td>Like &#039;grouping&#039; element.</td>
    </tr>

    <tr>
     <td>POSITIVE_SIGN</td>
     <td>Sign for positive values.</td>
    </tr>

    <tr>
     <td>NEGATIVE_SIGN</td>
     <td>Sign for negative values.</td>
    </tr>

    <tr>
     <td>INT_FRAC_DIGITS</td>
     <td>International fractional digits.</td>
    </tr>

    <tr>
     <td>FRAC_DIGITS</td>
     <td>Local fractional digits.</td>
    </tr>

    <tr>
     <td>P_CS_PRECEDES</td>
     <td>Returns 1 if CURRENCY_SYMBOL precedes a positive value.</td>
    </tr>

    <tr>
     <td>P_SEP_BY_SPACE</td>
     <td>Returns 1 if a space separates CURRENCY_SYMBOL from a positive value.</td>
    </tr>

    <tr>
     <td>N_CS_PRECEDES</td>
     <td>Returns 1 if CURRENCY_SYMBOL precedes a negative value.</td>
    </tr>

    <tr>
     <td>N_SEP_BY_SPACE</td>
     <td>Returns 1 if a space separates CURRENCY_SYMBOL from a negative value.</td>
    </tr>

    <tr>
     <td>P_SIGN_POSN</td>
     <td rowspan="2" style="vertical-align: middle;">
      <ul class="itemizedlist">
       <li class="listitem">
        <span class="simpara">
          Returns 0 if parentheses surround the quantity and currency_symbol.
        </span>
       </li>
       <li class="listitem">
        <span class="simpara">
         Returns 1 if the sign string precedes the quantity and currency_symbol.
        </span>
       </li>
       <li class="listitem">
        <span class="simpara">
         Returns 2 if the sign string follows the quantity and currency_symbol.
        </span>
       </li>
       <li class="listitem">
        <span class="simpara">
         Returns 3 if the sign string immediately precedes the currency_symbol.
        </span>
       </li>
       <li class="listitem">
        <span class="simpara">
         Returns 4 if the sign string immediately follows the currency_symbol.
        </span>
       </li>
      </ul>
     </td>
    </tr>

    <tr>
     <td>N_SIGN_POSN</td>
    </tr>

    <tr>
     <td colspan="2" style="text-align: center;"><em>LC_NUMERIC Category Constants</em></td>
    </tr>

    <tr>
     <td>DECIMAL_POINT</td>
     <td>Decimal point character.</td>
    </tr>

    <tr>
     <td>RADIXCHAR</td>
     <td>Same value as DECIMAL_POINT.</td>
    </tr>

    <tr>
     <td>THOUSANDS_SEP</td>
     <td>Separator character for thousands (groups of three digits).</td>
    </tr>

    <tr>
     <td>THOUSEP</td>
     <td>Same value as THOUSANDS_SEP.</td>
    </tr>

    <tr>
     <td>GROUPING</td>
     <td></td>
    </tr>

    <tr>
     <td colspan="2" style="text-align: center;"><em>LC_MESSAGES Category Constants</em></td>
    </tr>

    <tr>
     <td>YESEXPR</td>
     <td>Regex string for matching &#039;yes&#039; input.</td>
    </tr>

    <tr>
     <td>NOEXPR</td>
     <td>Regex string for matching &#039;no&#039; input.</td>
    </tr>

    <tr>
     <td>YESSTR</td>
     <td>Output string for &#039;yes&#039;.</td>
    </tr>

    <tr>
     <td>NOSTR</td>
     <td>Output string for &#039;no&#039;.</td>
    </tr>

    <tr>
     <td colspan="2" style="text-align: center;"><em>LC_CTYPE Category Constants</em></td>
    </tr>

    <tr>
     <td>CODESET</td>
     <td>Return a string with the name of the character encoding.</td>
    </tr>

   </tbody>
  
 </table>

</div>
