<?php
namespace phpdotnet\phd;
/* $Id: ChunkedXHTML.php 288433 2009-09-18 04:13:15Z moacir $ */

/**
 * Generates Atom feed of Table of Contents for
 * each chunk.
 *
 * @category PhD
 * @package  PhD_PEAR
 * @author   Christian Weiske <cweiske@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD Style
 * @link     http://doc.php.net/phd/
 */
class Package_PEAR_TocFeed extends Package_Generic_TocFeed
{
    /**
     * Name of TOC feed format used by PhD internally.
     *
     * Inheriting classes should change this.
     *
     * @var string
     */
    protected $formatName = 'PEAR-TocFeed';

    /**
     * File extension with leading dot for
     * links from atom feed to chunks.
     *
     * Inheriting classes should change this if neccessary.
     *
     * @var    string
     * @usedby createTargetLink()
     */
    protected $targetExt = '.php';

    /**
     * Base URI for links from atom feed to chunks.
     *
     * Inheriting classes should change this if neccessary.
     *
     * @var    string
     * @usedby createTargetLink()
     * @usedby createLink()
     */
    protected $targetBaseUri = 'http://pear.php.net/manual/{language}/';

    /**
     * Author string used in atom feed files.
     *
     * Inheriting classes should change this.
     * 
     * @var    string
     * @usedby header()
     */
    protected $author = 'PEAR Documentation Group';

    /**
     * Prefix for atom entry id values.
     *
     * @internal
     * We are using tag URIs here.
     * @link http://www.faqs.org/rfcs/rfc4151.html
     * @link http://diveintomark.org/archives/2004/05/28/howto-atom-id
     *
     * @var string
     */
    protected $idprefix = 'tag:pear.php.net,manual-{language},';



    /**
     * Create new instance.
     */
    public function __construct()
    {
        parent::__construct();

        $language = Config::language();
        $this->targetBaseUri = str_replace(
            '{language}', $language, $this->targetBaseUri
        );
        $this->idprefix = str_replace(
            '{language}', $language, $this->idprefix
        );
    }

}
