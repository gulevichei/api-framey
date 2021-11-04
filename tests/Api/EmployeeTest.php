<?php

use Symfony\Component\HttpFoundation\Response;

class EmployeeTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEmployee()
    {
        $this->get('/api/employee');
        $this->assertEquals(
            Response::HTTP_OK,
            $this->response->getStatusCode()
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNotFoundEmployee()
    {
        $this->get('/api/employee/1');
        $this->assertEquals(
            '{"message":"Not Found"}',
            $this->response->getContent()
        );
        $this->assertEquals(
            Response::HTTP_NOT_FOUND,
            $this->response->getStatusCode()
        );
    }
}
