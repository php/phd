<?php
require_once 'config.php';
require_once 'formats/xhtml.php';

$phd = new PhDXHTMLReader( "${OPTIONS[ 'xml_root' ]}/.manual.xml" );
$phd->seek( "function.dotnet-load" );
echo date( DATE_RSS )." done seeking\n";

ob_start();
while( $phd->nextNode() ) {
	print $phd->transform();
}
$phd->close();

?>
