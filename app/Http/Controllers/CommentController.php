<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ResponseController;

class CommentController extends ResponseController
{
    public function index() : JsonResponse {
        try {
            $comments = Comment::paginate(1);
            return $this->sendResponse($comments, 'Get data Comments successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Not Found', ['error'=>'Data Not Found']);
        }
    }

    public function show($id) : JsonResponse {
        try {
            $comment = Comment::find($id);
            return $this->sendResponse($comment, 'Get data Comment successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Not Found', ['error'=>'Comment Not Found']);
        }
    }

    public function store(Request $request) : JsonResponse {
        try {
            $validator = Validator::make($request->all(), [
                // 'user_id' => 'required|exists:users,id',
                'post_id' => 'required|exists:posts,id',
                'comment' => 'required',
                // 'c_password' => 'required|same:password',
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $input = $request->all();
            $input['user_id'] = Auth::user()->id;
            $comment = Comment::create($input);
            $success['data'] =  $comment;

            return $this->sendResponse($success, 'Data Comment added successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Error', ['error'=> $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id) : JsonResponse {
        try {
            $validator = Validator::make($request->all(), [
                // 'user_id' => 'required|exists:users,id',
                'post_id' => 'required|exists:posts,id',
                'comment' => 'required',
                // 'c_password' => 'required|same:password',
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $comment = Comment::find($id);
            $user = Auth::user()->id;

            $comment->update([
                'user_id' => $user,
                'post_id' => $request->post_id,
                'comment' => $request->comment
            ]);

            return $this->sendResponse($comment, 'Data Comment updated successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Error', ['error'=> $e->getMessage()], 500);
        }
    }

    public function destroy($id) : JsonResponse {
        try {
            $comment = Comment::find($id);
            $comment->delete();

            return $this->sendResponse('', 'Data Comment deleted successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Error', ['error'=> $e->getMessage()], 500);
        }
    }
}
