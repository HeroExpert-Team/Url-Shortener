<?php

use Carbon\Carbon;
use App\Models\Url;
use App\Models\User;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;

final class UserTest extends TestCase
{
    /**
     * Database setup before each test.
     * Starts a database transaction to roll back after each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        DB::beginTransaction();
    }

    /**
     * Rollback the database transaction and reset auto-increment values after each test.
     */
    protected function tearDown(): void
    {
        DB::rollBack();
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE urls AUTO_INCREMENT = 1');
        parent::tearDown();
    }

    /**
     * Test if a user can be created and saved in the database.
     */
    #[Test]
    public function itCanCreateAUser(): void
    {
        $user = $this->createUser('Amirreza', 'aabrahimi1718@example.com');

        $savedUser = User::find($user->id);

        $this->assertNotNull($savedUser);
        $this->assertEquals('aabrahimi1718@example.com', $savedUser->email);
        $this->assertEquals('101010', $savedUser->otpCode);
    }

    /**
     * Test if a user can have multiple URLs associated with them.
     */
    #[Test]
    public function userCanHaveMultipleUrls(): void
    {
        $user = $this->createUser('Amirreza', 'amirreza@example.com');

        $url1 = $this->createUrl($user, 'https://example.com/1', 'exmpl-1');
        $url2 = $this->createUrl($user, 'https://example.com/2', 'exmpl-2');

        $urls = $user->urls; # Assume urls method retrieves all URLs for this user

        $this->assertCount(2, $urls);
        $this->assertEquals($url1->url, $urls[0]->url);
        $this->assertEquals($url2->url, $urls[1]->url);
    }

    /**
     * Test that the email field in the User model is unique.
     */
    #[Test]
    public function emailMustBeUnique(): void
    {
        $this->expectException(\Exception::class);

        $this->createUser('First User', 'unique@example.com');
        $this->createUser('Second User', 'unique@example.com'); # Should throw exception
    }

    /**
     * Test that the OTP expiration time is set correctly and is in the future.
     */
    #[Test]
    public function otpExpirationIsSetCorrectly(): void
    {
        $user = $this->createUser('Amirreza', 'amirreza@example.com', '123456', Carbon::now()->addMinutes(10));

        $this->assertNotNull($user->otpExpired);
        $this->assertGreaterThan(Carbon::now(), $user->otpExpired);
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
