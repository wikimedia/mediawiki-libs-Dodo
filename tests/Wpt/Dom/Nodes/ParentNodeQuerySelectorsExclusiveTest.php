<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelectors-exclusive.html.
class ParentNodeQuerySelectorsExclusiveTest extends WptTestHarness
{
    public function testParentNodeQuerySelectorsExclusive()
    {
        $this->source_file = 'vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelectors-exclusive.html';
        $button = $this->doc->createElement('button');
        $this->assertEqualsData($button->querySelector('*'), null, "querySelector, '*', before modification");
        $this->assertEqualsData($button->querySelector('button'), null, "querySelector, 'button', before modification");
        $this->assertEqualsData($button->querySelector('button, span'), null, "querySelector, 'button, span', before modification");
        $this->assertArrayEqualsData($button->querySelectorAll('*'), [], "querySelectorAll, '*', before modification");
        $this->assertArrayEqualsData($button->querySelectorAll('button'), [], "querySelectorAll, 'button', before modification");
        $this->assertArrayEqualsData($button->querySelectorAll('button, span'), [], "querySelectorAll, 'button, span', before modification");
        $button->innerHTML = 'text';
        $this->assertEqualsData($button->querySelector('*'), null, "querySelector, '*', after modification");
        $this->assertEqualsData($button->querySelector('button'), null, "querySelector, 'button', after modification");
        $this->assertEqualsData($button->querySelector('button, span'), null, "querySelector, 'button, span', after modification");
        $this->assertArrayEqualsData($button->querySelectorAll('*'), [], "querySelectorAll, '*', after modification");
        $this->assertArrayEqualsData($button->querySelectorAll('button'), [], "querySelectorAll, 'button', after modification");
        $this->assertArrayEqualsData($button->querySelectorAll('button, span'), [], "querySelectorAll, 'button, span', after modification");
        $this->done();
    }
}
