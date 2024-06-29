<?php

namespace App\Tests\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetQuestionTest extends WebTestCase
{
    public function test_get_question_ok()
    {
        $question = [
            "id" => 312443,
            "title" => "How do I split a list into equally-sized chunks?",
            "body" => "<p>How do I split a list of arbitrary length into equal sized chunks?</p>
                    <p><strong>Related question:</strong> <a href=\"https://stackoverflow.com/q/434287/7758804\">How to iterate over a list in chunks</a></p>",
           "score" => 2912,
           "view_count" => 1460475,
           "answer_count" => 71,
           "comment_count" => 2,
           "tags" => [
                "python",
                "list",
                "split",
                "chunks"
            ],
           "creation_date" => "2008-11-23 12:15:52"
        ];

        $id = 312443;
        $client = static::createClient();
        $client->request('GET', "/questions/$id");
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals($question['id'], $responseData['id']);
        $this->assertEquals($question['title'], $responseData['title']);
        $this->assertEquals($question['score'], $responseData['score']);
        $this->assertEquals($question['view_count'], $responseData['view_count']);
        $this->assertEquals($question['answer_count'], $responseData['answer_count']);
        $this->assertEquals($question['comment_count'], $responseData['comment_count']);
        $this->assertEquals($question['tags'], $responseData['tags']);
        $this->assertEquals($question['creation_date'], $responseData['creation_date']);
    }

    public function test_get_question_not_found()
    {
        $client = static::createClient();

        $id = 312443432;
        $client->request('GET', "/questions/$id");

        $this->assertSame(404, $client->getResponse()->getStatusCode());
    }
}