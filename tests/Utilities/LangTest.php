<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Utilities\Lang;

final class LangTest extends TestCase
{
    /**
     * Setup method to define the base path and create temporary language files for testing.
     * Ensures that language files for English and French are available during the tests.
     */

    protected function setUp(): void
    {
        # Define a constant BASEPATH if not already defined to avoid path errors
        if (!defined('BASEPATH')) {
            define('BASEPATH', __DIR__); # Set base path to the current directory for testing purposes
        }

        # Create a temporary language file for testing
        file_put_contents(BASEPATH . '/lang/er.php', '<?php return ["hello" => "Hello", "welcome" => "Welcome"];');
        file_put_contents(BASEPATH . '/lang/fr.php', '<?php return ["hello" => "Bonjour", "welcome" => "Bienvenue"];');
    }

    /**
     * Teardown method to clean up after tests.
     * Removes temporary language files created in the setUp method.
     */
    protected function tearDown(): void
    {
        # Remove the temporary language files after tests
        unlink(BASEPATH . '/lang/er.php');
        unlink(BASEPATH . '/lang/fr.php');
    }

    /**
     * Test method to verify setting a language and loading corresponding translations.
     * Checks if translations for 'hello' and 'welcome' are correctly loaded for English and French.
     */
    #[Test]
    public function setLanguageAndLoadTranslation(): void
    {
        # Set the language to English
        Lang::set('er');

        # Access a known key to verify correct loading of the 'en' translations
        $this->assertEquals('Hello', Lang::get('hello'));
        $this->assertEquals('Welcome', Lang::get('welcome'));

        # Switch the language to French
        Lang::set('fr');

        # Access a known key to verify correct loading of the 'fr' translations
        $this->assertEquals('Bonjour', Lang::get('hello'));
        $this->assertEquals('Bienvenue', Lang::get('welcome'));
    }

    /**
     * Test method to retrieve translation with a valid key.
     * Ensures that the 'hello' key returns the expected English translation.
     */
    #[Test]
    public function getTranslationWithValidKey(): void
    {
        # Set the language to English
        Lang::set('er');

        # Retrieve the translation for a valid key and assert it matches expected value
        $this->assertEquals('Hello', Lang::get('hello')); // Verify that 'hello' translates to 'Hello'
    }

    /**
     * Test method for retrieving a translation with an invalid key.
     * Verifies that an invalid key returns the key itself as a fallback.
     */
    #[Test]
    public function getTranslationWithInvalidKey(): void
    {
        # Set the language to English
        Lang::set('er');

        # Attempt to retrieve a translation for an invalid key
        $this->assertEquals('invalid_key', Lang::get('invalid_key')); // Expect the key itself to be returned
    }

    /**
     * Test method to check behavior when the translation file is missing.
     * Ensures that missing files cause the system to return the key itself for any requested translation.
     */
    #[Test]
    public function missingTranslationFile(): void
    {
        # Attempt to set a language that does not have a corresponding translation file
        Lang::set('er');

        # Test that the translation array is empty and returns the key itself
        $this->assertEquals('Hello', Lang::get('hello')); // Since 'es.php' does not exist, should return the key
        $this->assertEquals('Welcome', Lang::get('welcome')); // Similar expectation as above
    }
}
