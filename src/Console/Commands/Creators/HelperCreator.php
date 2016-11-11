<?php

namespace Fuguevit\Support\Console\Commands\Creators;

use Doctrine\Common\Inflector\Inflector;
use Illuminate\Filesystem\Filesystem;

/**
 * Class HelperCreator
 *
 * @package Fuguevit\Support\Console\Commands\Creators
 */
class HelperCreator
{
    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @var
     */
    protected $helper;

    /**
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    /**
     * @return mixed
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * @param $helper
     */
    public function setHelper($helper)
    {
        $this->helper = $helper;
    }

    /**
     * Create the helper.
     *
     * @param $helper
     * @return null
     */
    public function create($helper)
    {
        $this->setHelper($helper);
        $this->createDirectory();
        return $this->createClass();
    }

    /**
     * Create the helper directory if not exist.
     *
     * @return null
     */
    protected function createDirectory()
    {
        $directory = $this->getDirectory();
        if (!$this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }
    }

    /**
     * Get the helper directory.
     *
     * @return mixed
     */
    protected function getDirectory()
    {
        return config('support.helper_path');
    }

    /**
     * Get the helper name.
     *
     * @return mixed|string
     */
    protected function getHelperName()
    {
        $helper_name = $this->getHelper();

        if (!strpos($helper_name, 'Helper') !== false) {
            $helper_name .= 'Helper';
        }
        return $helper_name;
    }

    /**
     * Get the stripped helper name.
     *
     * @return mixed
     */
    protected function stripHelperName()
    {
        $helper = strtolower($this->getHelper());
        $stripped = str_replace("helper", "", $helper);

        return ucfirst($stripped);
    }

    /**
     * Get the populate data.
     *
     * @return array
     */
    protected function getPopulateData()
    {
        $helper_namespace = config('support.helper_namespace');
        $helper_class = $this->getHelperName();

        $populate_data = [
            'helper_namespace' => $helper_namespace,
            'helper_class'     => $helper_class,
        ];

        return $populate_data;
    }

    /**
     * Get the path.
     *
     * @return string
     */
    protected function getPath()
    {
        $path = $this->getDirectory() . DIRECTORY_SEPARATOR . $this->getHelperName() . '.php';

        return $path;
    }

    /**
     * Get the stub.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub_path = __DIR__.'/../../../../resources/stubs/';

        $stub = $this->files->get($stub_path."helper.stub");

        return $stub;
    }

    /**
     * Populate the stub.
     *
     * @return string
     */
    protected function populateStub()
    {
        // Populate data
        $populate_data = $this->getPopulateData();

        // Stub
        $stub = $this->getStub();

        // Loop through the populate data.
        foreach ($populate_data as $key => $value) {
            // Populate the stub.
            $stub = str_replace($key, $value, $stub);
        }

        // Return the stub.
        return $stub;
    }

    protected function createClass()
    {
        return $this->files->put($this->getPath(), $this->populateStub());
    }
}