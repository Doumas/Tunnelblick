<?php

namespace App\Controllers;

use App\Models\BlogPost;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;

class BlogPostController
{
    public function index(Request $request, Response $response): Response
    {
        $posts = BlogPost::with('dj', 'media')->get();
        return $this->respondWithJson($response, $posts);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $post = BlogPost::with('dj', 'media')->find($args['id']);
        if (!$post) {
            return $this->respondWithJson($response, ['error' => 'Post not found'], 404);
        }
        return $this->respondWithJson($response, $post);
    }

    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $validation = $this->validate($data);
        if ($validation !== true) {
            return $this->respondWithJson($response, ['errors' => $validation], 422);
        }

        $post = BlogPost::create($data);
        return $this->respondWithJson($response, $post, 201);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $post = BlogPost::find($args['id']);
        if (!$post) {
            return $this->respondWithJson($response, ['error' => 'Post not found'], 404);
        }

        $data = $request->getParsedBody();

        $validation = $this->validate($data);
        if ($validation !== true) {
            return $this->respondWithJson($response, ['errors' => $validation], 422);
        }

        $post->update($data);
        return $this->respondWithJson($response, $post);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $post = BlogPost::find($args['id']);
        if (!$post) {
            return $this->respondWithJson($response, ['error' => 'Post not found'], 404);
        }

        $post->delete();
        return $response->withStatus(204); // No Content
    }

    protected function validate(array $data)
    {
        $validator = v::key('title', v::stringType()->length(1, 255))
                      ->key('main_image', v::optional(v::url()->length(1, 255)))
                      ->key('content', v::stringType()->notEmpty())
                      ->key('dj_id', v::optional(v::intVal()));

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
