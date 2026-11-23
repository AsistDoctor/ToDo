<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // Разрешаем массово заполнять только эти поля: Task::create([...]) с чужим полем бросит ошибку
    protected $fillable = ['title', 'done'];

    // Значение по умолчанию из миграции модель не видит: без этой строки новая задача ушла бы в ответе API без поля done
    protected $attributes = ['done' => false];

    protected function casts(): array
    {
        // В базе done лежит как 0/1, наружу отдаём настоящие true/false
        return ['done' => 'boolean'];
    }
}
