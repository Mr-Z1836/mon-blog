<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) $this->user()?->est_administrateur;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'video' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:51200'],
            'content' => ['required', 'string'],
            'content_html' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'cta_title' => ['nullable', 'string', 'max:255'],
            'cta_text' => ['nullable', 'string', 'max:100', 'required_with:cta_url'],
            'cta_url' => ['nullable', 'string', 'max:500', 'required_with:cta_text', 'regex:/^(https?:\/\/.+|\/.*)$/'],
            'youtube_url' => ['nullable', 'url', 'max:500'],
            'post_series_id' => ['nullable', 'exists:post_series,id'],
            'series_part' => ['nullable', 'integer', 'min:1'],
            'is_published' => ['nullable', 'boolean'],
            'is_pinned' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
        ];
    }
}
