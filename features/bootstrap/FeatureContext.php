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
        if('input' !== $el->getTagName()) {
            throw new Exception('Element with id "jq-primarySearch" should be an input');
        }
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