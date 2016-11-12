<?php

namespace Fuguevit\Support\Tests;

use Illuminate\Support\Facades\Artisan;

class MakeHelperCommandTest extends TestCase
{
    /**
     * Test it can call make:helper command.
     */
    public function test_make_helper_command()
    {
        Artisan::call('make:helper', [
            'helper' => 'Test'
        ]);

        $test_path = __DIR__.'/../app';
        $test_file = $test_path . DIRECTORY_SEPARATOR . 'Helpers/TestHelper.php';

        $this->assertFileExists($test_file);

        app()['FileSystem']->cleanDirectory($test_path);
    }

    /**
     * Test it can call make:helper command with options.
     */
    public function test_make_helper_command_with_option()
    {
        Artisan::call('make:helper', [
            'helper' => 'TestBeta',
            '--base' => 'Common'
        ]);

        $test_path = __DIR__.'/../app';
        $test_file = $test_path . DIRECTORY_SEPARATOR . 'Helpers/TestBetaHelper.php';

        $this->assertFileExists($test_file);

        app()['FileSystem']->cleanDirectory($test_path);
    }
}