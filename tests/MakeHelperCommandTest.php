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
}