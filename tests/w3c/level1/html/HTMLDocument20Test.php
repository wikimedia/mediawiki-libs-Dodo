<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLDocument20.js.
class HTMLDocument20Test extends DomTestCase
{
    public function testHTMLDocument20()
    {
        $builder = $this->getBuilder();
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
        $doc->open();
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
        $doc->close();
    }
}