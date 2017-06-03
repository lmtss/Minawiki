<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Page;


class SystemMessageTest extends BrowserKitTestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {

        //test store
        $this->withSession(['user.admin_name'=>'sb','user.power'=>2])
            ->json('POST','/admin/systemMessage',['id'=>11,'behavior'=>'testhavior','comment'=>'testcomment'])
            ->seeJson(['result'=>'true',]);
        $this->withSession(['user.admin_name'=>'sb','user.power'=>2])
            ->json('POST','/admin/systemMessage',['id'=>1231231,'behavior'=>'testhavior','comment'=>'testcomment'])
            ->seeJson(['result'=>'false']);

    }
}
