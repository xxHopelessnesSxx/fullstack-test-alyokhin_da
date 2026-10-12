<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateComments extends Migration
{
    // up() вызывается при применении миграции — создаём таблицу
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [            // email автора
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'text' => [            // текст комментария
                'type' => 'TEXT',
            ],
            'date' => [            // дата, которую выбирает автор
                'type' => 'DATE',
            ],
        ]);

        $this->forge->addKey('id', true); // true = первичный ключ
        $this->forge->addKey('date');     // индекс, чтобы быстро сортировать по дате
        $this->forge->createTable('comments');
    }

    // down() вызывается при откате миграции — удаляем таблицу
    public function down()
    {
        $this->forge->dropTable('comments', true); // true = «только если таблица существует»
    }
}