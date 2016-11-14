<?php

namespace Fuguevit\Support\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * {@inheritdoc}
     */
    protected function getPackageProviders($app)
    {
        return [
            'Fuguevit\Support\Providers\SupportServiceProvider',
        ];
    }
}
