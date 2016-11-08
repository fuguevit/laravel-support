<?php

namespace Fuguevit\Support\Tests;

use Fuguevit\Support\Helpers\CommonHelper;

class CommonHelperTest extends TestCase
{
    /**
     * Test gravatar function returns a gravatar image url.
     */
    public function test_it_can_call_gravatar()
    {
        $avatar = CommonHelper::gravatar('test@gmail');
        $this->assertStringStartsWith('https://www.gravatar.com/avatar/', $avatar);
    }

}