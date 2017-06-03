<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Page;
use App\Comment;


class NoticeTest extends BrowserKitTestCase
{
    use DatabaseMigrations;
    //test store
    public function testBasicTest()
    {
        //test store
        $this->withSession(['user.id'=>11,'user.power'=>2])
             ->json('POST','/test1/notice',['text'=>'testtext'])
             ->seeJson(['result'=>'true','msg'=>'success']);

        //test delete notice
        $notice = Comment::where('user_id',11)->first();
        $id = $notice->id;
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('DELETE', '/CommentTest233/notice/'.$id)
            ->seeJson([
                'result' => 'false',
                'msg' => 'invalid title',
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('DELETE', '/test1/notice/233333')
            ->seeJson([
                'result' => 'false',
                'msg' => 'invalid notice id',
            ]);
        $this->withSession(['user.id' => 123, 'user.power' => 1])
            ->json('DELETE', '/test1/notice/'.$id)
            ->seeJson([
                'result' => 'false',
                'msg' => 'unauthorized',
            ]);
        $this->withSession(['user.id' => 11, 'user.power' => 2])
            ->json('DELETE', '/test1/notice/'.$id)
            ->seeJson([
                'result' => 'true',
                'msg' => 'delete success',
            ]);
    }

    public function testAllUser()
    {
        $this->withSession(['user.id'=>11,'user.power'=>2])
            ->json('GET','/user/all')
            ->seeJson(['ss'=>'ss']);
    }
}
