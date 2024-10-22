<?php

namespace App\Controllers;

use App\Models\Comment;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;

class CommentController
{
    public function index(Request $request, Response $response): Response
    {
        $comments = Comment::with('user', 'post', 'video')->get();
        return $this->respondWithJson($response, $comments);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $comment = Comment::with('user', 'post', 'video')->find($args['id']);
        if (!$comment) {
            return $this->respondWithJson($response, ['error' => 'Comment not found'], 404);
        }
        return $this->respondWithJson($response, $comment);
    }

    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $validation = $this->validate($data);
        if ($validation !== true) {
            return $this->respondWithJson($response, ['errors' => $validation], 422);
        }

        $comment = Comment::create($data);
        return $this->respondWithJson($response, $comment, 201);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $comment = Comment::find($args['id']);
        if (!$comment) {
            return $this->respondWithJson($response, ['error' => 'Comment not found'], 404);
        }

        $data = $request->getParsedBody();

        $validation = $this->validate($data);
        if ($validation !== true) {
            return $this->respondWithJson($response, ['errors' => $validation], 422);
        }

        $comment->update($data);
        return $this->respondWithJson($response, $comment);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $comment = Comment::find($args['id']);
        if (!$comment) {
            return $this->respondWithJson($response, ['error' => 'Comment not found'], 404);
        }

        $comment->delete();
        return $response->withStatus(204); // No Content
    }

    protected function validate(array $data)
    {
        $validator = v::key('user_id', v::intVal()->notEmpty())
                      ->key('content', v::stringType()->notEmpty())
                      ->key('post_id', v::optional(v::intVal()))
                      ->key('video_id', v::optional(v::intVal()));

        try {
            $validator->assert($data);
            return true;
        } catch (\Respect\Validation\Exceptions\ValidationException $e) {
            return $e->getMessage();
        }
    }

    protected function respondWithJson(Response $response, $data, int $status = 200): Response
    {
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus($status);
    }
}
