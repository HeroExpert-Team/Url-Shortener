<?php

use Carbon\Carbon;
use App\Models\Url;
use App\Models\User;
use App\Models\View;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;

class ViewTest extends TestCase
{
    /**
     * Set up the testing environment before each test.
     * This method is executed before each test method is called.
     * It begins a database transaction to ensure the database remains 
     * consistent during the test and is rolled back after each test.
     */
    public function setUp(): void
    {
        parent::setUp();
        DB::beginTransaction();
    }

    /**
     * Roll back the transaction after each test to maintain a clean state.
     * This method is executed after each test method is called.
     */
    public function tearDown(): void
    {
        DB::rollBack();
        # Reset auto-increment for tables
        DB::statement('ALTER TABLE views AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE urls AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');
        parent::tearDown();
    }

    /**
     * Test creating a View record and asserting its attributes.
     * This test ensures that a new View can be created successfully,
     * and its attributes are correctly assigned and accessible.
     */
    #[Test]
    public function itCanCreateView()
    {
        # Arrange: Create a (URL & User) and associate it with a View
        $user = $this->createUser('Amirreza Ebrahimi', 'aabrahimi1718@gmail.com');
        $url = $this->createUrl($user, 'https://example', 'example');

        # Act: Create a View record
        $view = View::create([
            'url_id' => $url->id,
            'ip_address' => '192.168.1.1',
            'user_agent' => 'Mozilla/5.0',
            'created_at' => Carbon::now(), # Using standard DateTime
        ]);

        # Assert: Check that the attributes were assigned correctly
        $this->assertEquals($url->id, $view->url_id);
        $this->assertEquals('192.168.1.1', $view->ip_address);
        $this->assertEquals('Mozilla/5.0', $view->user_agent);
    }

    /**
     * Test the relationship between View and Url.
     * This test ensures that a View can correctly retrieve the associated Url 
     * details via the 'url' method.
     */
    #[Test]
    public function viewBelongsToUrl()
    {
        # Arrange: Create a (URL & User) and associate it with a View
        $user = $this->createUser('Amirreza Ebrahimi', 'aabrahimi1718@gmail.com');
        $url = $this->createUrl($user, 'https://example', 'example');

        # Act: Create a View record
        $view = View::create([
            'url_id' => $url->id,
            'ip_address' => '192.168.1.1',
            'user_agent' => 'Mozilla/5.0',
            'created_at' => Carbon::now(), # Using standard DateTime
        ]);

        # Assert: Check that the associated URL details are correct
        $this->assertEquals($url->url, $view->url->url);
        $this->assertEquals($url->shortUrl, $view->url->shortUrl);
        $this->assertEquals($url->qrCode, $view->url->qrCode);
    }

    /**
     * Test the creation of a View with an invalid 'url_id'.
     * This test ensures that attempting to create a View with a non-existent URL ID
     * triggers a database exception.
     */
    #[Test]
    public function invalidUrlId()
    {
        $this->expectException(\PDOException::class); # Using PDOException instead of Laravel exceptions

        # Act: Try to create a View with a non-existent URL ID
        View::create([
            'url_id' => 9999, # Invalid URL ID
            'ip_address' => '192.168.1.1',
            'user_agent' => 'Mozilla/5.0',
            'created_at' => Carbon::now(),
        ]);
    }

    /**
     * Test the creation of a View with missing required fields.
     * This test ensures that attempting to create a View without essential fields,
     * like 'url_id', results in a database exception.
     */
    #[Test]
    public function testCreateViewWithMissingData()
    {
        $this->expectException(\PDOException::class); # Using PDOException instead of Laravel exceptions

        # Act: Try to create a View without the required 'url_id' field
        View::create([
            'ip_address' => '192.168.1.1',
            'user_agent' => 'Mozilla/5.0',
            'created_at' => Carbon::now(),
        ]);
    }

    /**
     * Helper function to create a User instance.
     */
    private function createUser(string $name, string $email, string $otpCode = '101010', Carbon $otpExpired = null): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'otpCode' => $otpCode,
            'otpExpired' => $otpExpired ?: Carbon::now()->addMinutes(10),
            'created_at' => Carbon::now(),
        ]);
    }

    /**
     * Helper function to create a Url instance.
     */
    private function createUrl(User $user, string $url, string $shortUrl): Url
    {
        return Url::create([
            'created_by' => $user->id,
            'url' => $url,
            'shortUrl' => $shortUrl,
            'qrCode' => 'qrcode-' . rand(1, 100),
            'views' => 0,
            'created_at' => Carbon::now(),
        ]);
    }
}
