<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\W3C\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLDocument19.js.
class HTMLDocument19Test extends W3CTestHarness
{
    public function testHTMLDocument19()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLDocument19') != null) {
            return;
        }
        $doc = null;
        $docElem = null;
        $title = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'document');
        // $doc->open();
        if ($builder->contentType == 'text/html') {
            $doc->write('<html>');
        } else {
            $doc->write("<html xmlns='http://www.w3.org/1999/xhtml'>");
        }
        $doc->write('<body>');
        $doc->write('<title>Replacement</title>');
        $doc->write('</body>');
        $doc->write('<p>');
        $doc->write('Hello, World.');
        $doc->write('</p>');
        $doc->write('</body>');
        $doc->write('</html>');
        // $doc->close();
    }
}
