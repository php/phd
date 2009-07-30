<?php
namespace phpdotnet\phd;
/*  $Id$ */
//6271

class Reader extends \XMLReader
{
    const XMLNS_XML     = "http://www.w3.org/XML/1998/namespace";
    const XMLNS_XLINK   = "http://www.w3.org/1999/xlink";
    const XMLNS_PHD     = "http://www.php.net/ns/phd";
    const XMLNS_DOCBOOK = "http://docbook.org/ns/docbook";

    public function __construct() {
    }

    public function close() {
        return parent::close();
    }

    public function getAttribute($name) {
        return parent::getAttribute($name);
    }

    public function getAttributeNo($index) {
        return parent::getAttributeNo($index);
    }

    public function getAttributeNs($name, $namespaceURI) {
        return parent::getAttributeNs($name, $namespaceURI);
    }

    public function getParserProperty($property) {
        return parent::getParserProperty($property);
    }

    public function isValid() {
        return parent::isValid();
    }

    public function lookupNamespace($prefix) {
        return parent::lookupNamespace($prefix);
    }

    public function moveToAttributeNo($index) {
        return parent::moveToAttributeNo($index);
    }

    public function moveToAttribute($name) {
        return parent::moveToAttribute($name);
    }

    public function moveToAttributeNs($name, $namespaceURI) {
        return parent::moveToAttributeNs($name, $namespaceURI);
    }

    public function moveToElement() {
        return parent::moveToElement();
    }

    public function moveToFirstAttribute() {
        return parent::moveToFirstAttribute();
    }

    public function moveToNextAttribute() {
        return parent::moveToNextAttribute();
    }

    public function open($URI, $encoding = null, $options = null) {
        return parent::open($URI, $encoding, $options);
    }

    public function read() {
        return parent::read();
    }

    public function next($localname = null) {
        return parent::next($localname);
    }

    public function readInnerXml() {
        return parent::readInnerXml();
    }

    public function readOuterXml() {
        return parent::readOuterXml();
    }

    public function readString() {
        return parent::readString();
    }

    public function setSchema($filename) {
        return parent::setSchema($filename);
    }

    public function setParserProperty($property, $value) {
        return parent::setParserProperty($property, $value);
    }

    public function setRelaxNGSchema($filename) {
        return parent::setRelaxNGSchema($filename);
    }

    public function setRelaxNGSchemaSource($source) {
        return parent::setRelaxNGSchemaSource($source);
    }

    public function XML($source, $encoding = null, $options = null) {
        return parent::XML($source, $encoding, $options);
    }

    public function expand() {
        return parent::expand();
    }

}


/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

