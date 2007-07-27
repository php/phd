<?php
require "include/PhDReader.class.php";
require "formats/xhtml.php";


$phd = new PhDXHTMLReader("/home/bjori/php/doc/.manual.xml");
$phd->seek("function.dotnet-load");
echo date(DATE_RSS), " done seeking\n";

ob_start();
while($phd->nextNode()) {
	print $phd->transform();
}
$phd->close();

