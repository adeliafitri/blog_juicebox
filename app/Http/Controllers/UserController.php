<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\ResponseController;

class UserController extends ResponseController
{
    public function show($id) : JsonResponse {
        try {
            $user = User::find($id);
            return $this->sendResponse($user, 'Get data user successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Not Found', ['error'=>'User Not Found']);
        }
    }
}
