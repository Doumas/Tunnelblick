<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\User;
use Respect\Validation\Validator as v;

class AuthController
{
    public function login(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        // Benutzer anhand der E-Mail oder des Benutzernamens finden
        $user = User::where('email', $data['email'])->orWhere('username', $data['username'])->first();
        
        // Überprüfung der Anmeldeinformationen
        if ($user && password_verify($data['password'], $user->password)) {
            // Benutzer ist authentifiziert, setze Sitzung
            $_SESSION['user_id'] = $user->user_id;
            $_SESSION['username'] = $user->username;
            $_SESSION['role'] = $user->role;

            return $this->jsonResponse($response, ['message' => 'Login successful', 'role' => $user->role], 200);
        }

        return $this->jsonResponse($response, ['error' => 'Invalid credentials'], 401);
    }

    public function register(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        // Validierung
        $validation = $this->validateRegistrationData($data);
        if (!empty($validation)) {
            return $this->jsonResponse($response, ['errors' => $validation], 400);
        }

        // Hash des Passworts
        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);

        // Benutzer erstellen
        $user = User::create([
            'username' => $data['username'],
            'password' => $passwordHash,
            'email' => $data['email'],
            'role' => 'member',  // Standardmäßig 'member'
        ]);

        return $this->jsonResponse($response, ['message' => 'Registration successful'], 201);
    }

    private function validateRegistrationData(array $data): array
    {
        $errors = [];

        if (!v::email()->validate($data['email'] ?? '')) {
            $errors['email'] = 'Invalid email address';
        }

        if (!v::stringType()->length(6, null)->validate($data['password'] ?? '')) {
            $errors['password'] = 'Password must be at least 6 characters long';
        }

        if (!v::stringType()->length(3, null)->validate($data['username'] ?? '')) {
            $errors['username'] = 'Username must be at least 3 characters long';
        }

        return $errors;
    }

    private function jsonResponse(Response $response, $data, int $status = 200): Response
    {
        $response->getBody()->write(
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
