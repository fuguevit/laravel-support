<?php

namespace Fuguevit\Support\Tests;

use Fuguevit\Support\Helpers\ResponseHelper;

class ResponseHelperTest extends TestCase
{
    /**
     * Test ResponseHelper can generate json style success string.
     */
    public function test_it_can_generate_success_json()
    {
        $result = ResponseHelper::success();

        $this->assertInstanceOf('Illuminate\Http\JsonResponse', $result);

        $expected = '{"status":"success","data":null,"message":null}';
        $this->assertJsonStringEqualsJsonString($expected, $result->content());
    }

    /**
     * Test ResponseHelper can generate json style error result.
     */
    public function test_it_can_generate_error_result()
    {
        $result = ResponseHelper::error(620, 'test error!');

        $expected = '{"status":"error", "message":"test error!", "error_code":620, "data":null}';
        $this->assertJsonStringEqualsJsonString($expected, $result->content());
    }

}