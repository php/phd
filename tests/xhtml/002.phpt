--TEST--
CALS Table rendering#002
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../setup.php";
require_once __DIR__ . "/TestChunkedXHTML.php";

$xml_file = __DIR__ . "/data/002.xml";

Config::init([
    "index"             => true,
    "xml_root"          => dirname($xml_file),
    "xml_file"          => $xml_file,
    "output_dir"        => __DIR__ . "/output/",
    "lang_dir" => __PHDDIR__ . "phpdotnet/phd/data/langs/",
    "phpweb_version_filename" => dirname($xml_file) . '/version.xml',
    "phpweb_acronym_filename" => dirname($xml_file) . '/acronyms.xml',
]);

$format = new TestChunkedXHTML;
$render = new TestRender($format, new Config);

if (Index::requireIndexing() && !file_exists($opts["output_dir"])) {
    mkdir($opts["output_dir"], 0755);
}

$render->run();
?>
--EXPECT--
Filename: function.db2-set-option.html
Content:
<div id="function.db2-set-option" class="article">
 <table class="doctable table">
   <caption><strong>Resource-Parameter Matrix</strong></caption>
   
     <col style="text-align: center;" />
     <col style="text-align: center;" />
     <col style="text-align: center;" />
     <col style="text-align: center;" />
     <col style="text-align: center;" />
     <thead>
       <tr>
         <th>Key</th>
         <th>Value</th>
         <th colspan="3">Resource Type</th>
       </tr>

     </thead>


     <tbody class="tbody">
       <tr>
         <td class="empty">&nbsp;</td><td class="empty">&nbsp;</td><td>Connection</td>
         <td>Statement</td>
         <td>Result Set</td>
       </tr>

       <tr>
         <td>autocommit</td>
         <td><code class="literal">DB2_AUTOCOMMIT_ON</code></td>
         <td>X</td>
         <td>-</td>
         <td>-</td>
       </tr>

       <tr>
         <td>autocommit</td>
         <td><code class="literal">DB2_AUTOCOMMIT_OFF</code></td>
         <td>X</td>
         <td>-</td>
         <td>-</td>
       </tr>

       <tr>
         <td>cursor</td>
         <td><code class="literal">DB2_SCROLLABLE</code></td>
         <td>-</td>
         <td>X</td>
         <td>-</td>
       </tr>

       <tr>
         <td>cursor</td>
         <td><code class="literal">DB2_FORWARD_ONLY</code></td>
         <td>-</td>
         <td>X</td>
         <td>-</td>
       </tr>

       <tr>
         <td>binmode</td>
         <td><code class="literal">DB2_BINARY</code></td>
         <td>X</td>
         <td>X</td>
         <td>-</td>
       </tr>

       <tr>
         <td>binmode</td>
         <td><code class="literal">DB2_CONVERT</code></td>
         <td>X</td>
         <td>X</td>
         <td>-</td>
       </tr>

       <tr>
         <td>binmode</td>
         <td><code class="literal">DB2_PASSTHRU</code></td>
         <td>X</td>
         <td>X</td>
         <td>-</td>
       </tr>

       <tr>
         <td>db2_attr_case</td>
         <td><code class="literal">DB2_CASE_LOWER</code></td>
         <td>X</td>
         <td>X</td>
         <td>-</td>
       </tr>

       <tr>
         <td>db2_attr_case</td>
         <td><code class="literal">DB2_CASE_UPPER</code></td>
         <td>X</td>
         <td>X</td>
         <td>-</td>
       </tr>

       <tr>
         <td>db2_attr_case</td>
         <td><code class="literal">DB2_CASE_NATURAL</code></td>
         <td>X</td>
         <td>X</td>
         <td>-</td>
       </tr>

       <tr>
         <td>deferred_prepare</td>
         <td><code class="literal">DB2_DEFERRED_PREPARE_ON</code></td>
         <td>-</td>
         <td>X</td>
         <td>-</td>
       </tr>

       <tr>
         <td>deferred_prepare</td>
         <td><code class="literal">DB2_DEFERRED_PREPARE_OFF</code></td>
         <td>-</td>
         <td>X</td>
         <td>-</td>
       </tr>

       <tr>
         <td>i5_fetch_only</td>
         <td><code class="literal">DB2_I5_FETCH_ON</code></td>
         <td>-</td>
         <td>X</td>
         <td>-</td>
       </tr>

       <tr>
         <td>i5_fetch_only</td>
         <td><code class="literal">DB2_I5_FETCH_OFF</code></td>
         <td>-</td>
         <td>X</td>
         <td>-</td>
       </tr>

       <tr>
         <td>userid</td>
         <td><code class="literal">SQL_ATTR_INFO_USERID</code></td>
         <td>X</td>
         <td>X</td>
         <td>-</td>
       </tr>

       <tr>
         <td>acctstr</td>
         <td><code class="literal">SQL_ATTR_INFO_ACCTSTR</code></td>
         <td>X</td>
         <td>X</td>
         <td>-</td>
       </tr>

       <tr>
         <td>applname</td>
         <td><code class="literal">SQL_ATTR_INFO_APPLNAME</code></td>
         <td>X</td>
         <td>X</td>
         <td>-</td>
       </tr>

       <tr>
         <td>wrkstnname</td>
         <td><code class="literal">SQL_ATTR_INFO_WRKSTNNAME</code></td>
         <td>X</td>
         <td>X</td>
         <td>-</td>
       </tr>

     </tbody>
   
 </table>

</div>
