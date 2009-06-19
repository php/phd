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
        return XMLReader::close();
    }

    public function getAttribute($name) {
        return XMLReader::getAttribute($name);
    }

    public function getAttributeNo($index) {
        return XMLReader::getAttributeNo($index);
    }

    public function getAttributeNs($name, $namespaceURI) {
        return XMLReader::getAttributeNs($name, $namespaceURI);
    }

    public function getParserProperty($property) {
        return XMLReader::getParserProperty($property);
    }

    public function isValid() {
        return XMLReader::isValid();
    }

    public function lookupNamespace($prefix) {
        return XMLReader::lookupNamespace($prefix);
    }

    public function moveToAttributeNo($index) {
        return XMLReader::moveToAttributeNo($index);
    }

    public function moveToAttribute($name) {
        return XMLReader::moveToAttribute($name);
    }

    public function moveToAttributeNs($name, $namespaceURI) {
        return XMLReader::moveToAttributeNs($name, $namespaceURI);
    }

    public function moveToElement() {
        return XMLReader::moveToElement();
    }

    public function moveToFirstAttribute() {
        return XMLReader::moveToFirstAttribute();
    }

    public function moveToNextAttribute() {
        return XMLReader::moveToNextAttribute();
    }

    public function open($URI, $encoding = null, $options = null) {
        return XMLReader::open($URI, $encoding, $options);
    }

    public function read() {
        return XMLReader::read();
    }

    public function next($localname = null) {
        return XMLReader::next($localname);
    }

    public function readInnerXml() {
        return XMLReader::readInnerXml();
    }

    public function readOuterXml() {
        return XMLReader::readOuterXml();
    }

    public function readString() {
        return XMLReader::readString();
    }

    public function setSchema($filename) {
        return XMLReader::setSchema($filename);
    }

    public function setParserProperty($property, $value) {
        return XMLReader::setParserProperty($property, $value);
    }

    public function setRelaxNGSchema($filename) {
        return XMLReader::setRelaxNGSchema($filename);
    }

    public function setRelaxNGSchemaSource($source) {
        return XMLReader::setRelaxNGSchemaSource($source);
    }

    public function XML($source, $encoding = null, $options = null) {
        return XMLReader::XML($source, $encoding, $options);
    }

    public function expand() {
        return XMLReader::expand();
    }

}

