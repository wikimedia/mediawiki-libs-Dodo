<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\DomException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLDocument20.js.
class HTMLDocument20Test extends W3CTestHarness
{
    public function testHTMLDocument20()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLDocument20') != null) {
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
            $doc->writeln('<html>');
        } else {
            $doc->writeln("<html xmlns='http://www.w3.org/1999/xhtml'>");
        }
        $doc->writeln('<body>');
        $doc->writeln('<title>Replacement</title>');
        $doc->writeln('</body>');
        $doc->writeln('<p>');
        $doc->writeln('Hello, World.');
        $doc->writeln('</p>');
        $doc->writeln('</body>');
        $doc->writeln('</html>');
        // $doc->close();
    }
}
