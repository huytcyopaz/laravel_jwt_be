<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
//    private UserRepository $userRepository;
    /**
     * Constructor
     */
    public function __construct(protected UserRepository $userRepository) {
//        $this->userRepository = new UserRepository(new User());
    }
    /**
     * Register a User.
     *
     * @param array $data
     * @return User
     */
    public function register(array $data): User
    {
        return $this->userRepository->store($data);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @param array $data
     * @return JsonResponse
     * @throws Exception
     */
    public function login(array $data): JsonResponse
    {
        $user = $this->userRepository->findByEmail($data['email']);
        if (! $token = auth()->attempt($data)) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }
        return $this->createNewToken($token);
    }
    /**
     * Create a new token
     *
     * @param string $token
     * @return JsonResponse
     */
    private function createNewToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

}
