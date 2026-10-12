<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table         = 'comments';             
    protected $primaryKey    = 'id';               
    protected $returnType    = 'array';                
    protected $allowedFields = ['name', 'text', 'date']; 
   
    protected $validationRules = [
        'name' => 'required|valid_email|max_length[255]',
        'text' => 'required|max_length[5000]',
        'date' => 'required|valid_date[Y-m-d]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'    => 'Укажите email.',
            'valid_email' => 'Введите корректный email, например user@mail.ru.',
            'max_length'  => 'Email слишком длинный.',
        ],
        'text' => [
            'required'   => 'Введите текст комментария.',
            'max_length' => 'Комментарий слишком длинный (максимум 5000 символов).',
        ],
        'date' => [
            'required'   => 'Укажите дату.',
            'valid_date' => 'Некорректная дата.',
        ],
    ];
}