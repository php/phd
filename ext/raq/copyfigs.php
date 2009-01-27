<?php
/* $Id$ */

/**
  * This is a simple PHP script which will copy the images from the PHPDOC checkout to the appropriate places for the built manuals.
  * Currently the paths are hard-coded, and only deals with a single language.
  * Ideally, I'd like to be able to get the data from some sort phd repository (say when phd runs, the config is saved, so it can be used by external scripts).
  */

// Define the source and destinations for each checkout type - currently only PHPDOC.
$a_Checkouts = array (
  'PHPDOC' => array (
    'Source' => 'D:/Personal Files/Downloads/Software/Programming/PHP/Checkouts/phpdoc/en/reference',
    'Destinations' => array (
      'D:/Personal Files/Downloads/Software/Programming/PHP/Manual/phpdoc/chm/res/figures',
      'D:/Personal Files/Downloads/Software/Programming/PHP/Manual/phpdoc/html/figures',
      'D:/Personal Files/Downloads/Software/Programming/PHP/Manual/phpdoc/php/figures',
    ),
  ),
);

// Process each source.
foreach($a_Checkouts as $s_Location => $a_Checkout) {

  // Process all the files in the checkout.
  foreach(scandir($a_Checkout['Source']) as $s_Book) {

    // Process each book
    if (!in_array($s_Book, array('.', '..')) && is_dir($a_Checkout['Source'] . DIRECTORY_SEPARATOR . $s_Book)) {

      // Scan book for xml files.
      foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($a_Checkout['Source'] . DIRECTORY_SEPARATOR . $s_Book)) as $o_File) {

        // Make sure they are XML files and that there are imagedata tags.
        if ('.xml' === substr($o_File, -4) && 0 !== preg_match_all('`<imagedata fileref="(figures/[^"]++)" ?/>`sim', file_get_contents($o_File->getPathname()), $a_Matches)) {

          // Process each image.
          foreach($a_Matches[1] as $s_Match) {

            // Build name to the physical image file.
            $s_SourceImageFilename = realpath(implode(DIRECTORY_SEPARATOR, array($a_Checkout['Source'], $s_Book, str_replace("{$s_Book}.", '', $s_Match))));

            echo
              PHP_EOL,
              'XML    : ', $o_File->getRealPath(), PHP_EOL,
              'Image  : ', $s_Match, PHP_EOL,
              'Source : ', $s_SourceImageFilename, PHP_EOL;

            // Copy the image to each destination and rename it appropriately.
            foreach($a_Checkout['Destinations'] as $s_Destination) {

              // Make sure the image folder exists.
              if (False === realpath($s_Destination) && !mkdir($s_Destination, 0x777, True)) {
                die("Unable to create $s_Destination");
              }

              // Build the destination image filename.
              $s_DestImageFilename = realpath($s_Destination) . DIRECTORY_SEPARATOR . basename($s_Match);

              echo
               'Dest   : ', $s_DestImageFilename, PHP_EOL;

              if (False === copy ($s_SourceImageFilename, $s_DestImageFilename)) {
                echo 'Failed', PHP_EOL;
              }

            } // End of destinations

          } // End of images

        }

      } // End of book scan

    }

  } // End of books

} // End of checkouts
