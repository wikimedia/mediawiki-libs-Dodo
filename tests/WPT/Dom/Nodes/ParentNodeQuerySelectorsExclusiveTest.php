<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelectors-exclusive.html.
class ParentNodeQuerySelectorsExclusiveTest extends WPTTestHarness
{
    public function testParentNodeQuerySelectorsExclusive()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/ParentNode-querySelectors-exclusive.html');
        $button = $this->doc->createElement('button');
        $this->wptAssertEquals($button->querySelector('*'), null, "querySelector, '*', before modification");
        $this->wptAssertEquals($button->querySelector('button'), null, "querySelector, 'button', before modification");
        $this->wptAssertEquals($button->querySelector('button, span'), null, "querySelector, 'button, span', before modification");
        $this->wptAssertArrayEquals($button->querySelectorAll('*'), [], "querySelectorAll, '*', before modification");
        $this->wptAssertArrayEquals($button->querySelectorAll('button'), [], "querySelectorAll, 'button', before modification");
        $this->wptAssertArrayEquals($button->querySelectorAll('button, span'), [], "querySelectorAll, 'button, span', before modification");
        $button->innerHTML = 'text';
        $this->wptAssertEquals($button->querySelector('*'), null, "querySelector, '*', after modification");
        $this->wptAssertEquals($button->querySelector('button'), null, "querySelector, 'button', after modification");
        $this->wptAssertEquals($button->querySelector('button, span'), null, "querySelector, 'button, span', after modification");
        $this->wptAssertArrayEquals($button->querySelectorAll('*'), [], "querySelectorAll, '*', after modification");
        $this->wptAssertArrayEquals($button->querySelectorAll('button'), [], "querySelectorAll, 'button', after modification");
        $this->wptAssertArrayEquals($button->querySelectorAll('button, span'), [], "querySelectorAll, 'button, span', after modification");
        $this->done();
    }
}
