<?php

namespace App\Http\Controllers;

use App\Requests\UserRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use App\Services\AuthService;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(protected AuthService $authService) {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     * @throws Exception
     */
    public function login(Request $request): JsonResponse
    {
        $data = $request->all();
        $user = $this->authService->login($data);
        return response()->json($user);
    }
    /**
     * Register a User.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
   public function register(UserRequest $request): JsonResponse
    {
        try {
            $data = $request->all();
            $user = $this->authService->register($data);
            return response()->json([
                'message' => 'User successfully registered',
                'user' => $user
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @param bool $token
     * @return JsonResponse
     */
    public function logout(bool $token): JsonResponse
    {
        auth()->logout($token);
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function userProfile(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Create a new token.
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
