<?php

namespace App\Http\Requests;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $comment = $this->route('comment');

        return $comment instanceof Comment
            && $this->user() !== null
            && $comment->user_id === $this->user()->id
            && $comment->post_id === $this->route('post')?->id;
    }

    public function rules(): array
    {
        return [
            'comment_content' => ['required', 'string', 'min:3', 'max:2000'],
        ];
    }
}
