<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = ['question', 'answer', 'question_en', 'answer_en', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean', 'sort_order' => 'integer'];

    public function getQuestionAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->question_en ?: $value;
        }
        return $value;
    }

    public function getAnswerAttribute($value)
    {
        if (app()->getLocale() === 'en') {
            return $this->answer_en ?: $value;
        }
        return $value;
    }
}
