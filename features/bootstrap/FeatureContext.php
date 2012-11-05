<?php

use Behat\MinkExtension\Context\MinkContext;

// Browser drivers
require_once(__DIR__ . '/../../mink.phar');
require_once(__DIR__ . '/../../mink_extension.phar');

/**
 * Features context.
 */
class FeatureContext extends MinkContext
{
    private $output;

    /** @When /^I play with the browser$/ */
    public function iPlayWithTheBrowser() {
        $this->getSession()->visit($this->locatePath('/'));
        // get the current page URL:
        $page = $this->getSession()->getPage();

        // El
        $el = $page->findById('jq-primarySearch');
        echo 'Tag Name: ' . $el->getTagName() . PHP_EOL; // undefined function getTagName() on a non-object

        // Get driver name
        $reflection = new \ReflectionObject($this->getSession());
        $prop = $reflection->getProperty('driver');
        $prop->setAccessible(true);
        echo get_class($prop->getValue($this->getSession())) . PHP_EOL;
    }

    /** @Given /^I am in a directory "([^"]*)"$/ */
    public function iAmInADirectory($dir)
    {
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        chdir($dir);
    }

    /** @Given /^I have a file named "([^"]*)"$/ */
    public function iHaveAFileNamed($file)
    {
        touch($file);
    }

    /** @When /^I run "([^"]*)"$/ */
    public function iRun($command)
    {
        exec($command, $output);
        $this->output = trim(implode("\n", $output));
    }

    /** @Then /^I should get:$/ */
    public function iShouldGet($string)
    {
        if ((string) $string !== $this->output) {
            throw new Exception(
                "Actual output is:\n" . $this->output
            );
        }
    }
}