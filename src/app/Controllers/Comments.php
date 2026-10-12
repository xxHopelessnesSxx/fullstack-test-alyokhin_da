<?php

namespace App\Controllers;

use App\Models\CommentModel;

class Comments extends BaseController
{
    private const PER_PAGE = 3;

    
    public function index()
    {
        return view('comments/index');
    }

    
    public function items()
    {
        $model = new CommentModel();

        $sort  = $this->request->getGet('sort') === 'date' ? 'date' : 'id';
        $order = $this->request->getGet('order') === 'asc' ? 'asc' : 'desc';
        $page  = max(1, (int) $this->request->getGet('page'));

        $total = $model->countAllResults();
        $pages = max(1, (int) ceil($total / self::PER_PAGE));
        $page  = min($page, $pages); 
        $model->orderBy($sort, $order);
        if ($sort === 'date') {
           
            $model->orderBy('id', $order);
        }

        $comments = $model->findAll(self::PER_PAGE, ($page - 1) * self::PER_PAGE);

        return $this->response->setJSON([
            'comments' => $comments,
            'page'     => $page,
            'pages'    => $pages,
            'total'    => $total,
        ]);
    }

    public function create()
    {
        $model = new CommentModel();

        $data = [
            'name' => trim((string) $this->request->getPost('name')),
            'text' => trim((string) $this->request->getPost('text')),
            'date' => (string) $this->request->getPost('date'),
        ];

        if (! $model->insert($data)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['errors' => $model->errors()]);
        }

        return $this->response
            ->setStatusCode(201) 
            ->setJSON(['id' => $model->getInsertID()]);
    }

    public function delete($id)
    {
        $model = new CommentModel();

        if ($model->find($id) === null) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['error' => 'Комментарий не найден.']);
        }

        $model->delete($id);

        return $this->response->setJSON(['success' => true]);
    }
}