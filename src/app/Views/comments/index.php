<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Комментарии</title>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>

        .comment-text { white-space: pre-line; word-break: break-word; }
    </style>
</head>
<body class="bg-light">

<div class="container py-4" style="max-width: 760px;">
    <h1 class="h3 mb-4">Комментарии</h1>


    <div class="form-row mb-3">
        <div class="col-sm-6 mb-2 mb-sm-0">
            <label for="sort" class="small text-muted mb-1">Сортировать по</label>
            <select id="sort" class="custom-select">
                <option value="id">ID</option>
                <option value="date">Дате</option>
            </select>
        </div>
        <div class="col-sm-6">
            <label for="order" class="small text-muted mb-1">Направление</label>
            <select id="order" class="custom-select">
                <option value="desc">По убыванию</option>
                <option value="asc">По возрастанию</option>
            </select>
        </div>
    </div>

    <div id="alert" class="alert d-none" role="alert"></div>

    <div id="comments-list"></div>

    <nav aria-label="Страницы комментариев">
        <ul id="pagination" class="pagination justify-content-center flex-wrap"></ul>
    </nav>

    <div class="card mt-4">
        <div class="card-body">
            <h2 class="h5 mb-3">Добавить комментарий</h2>

            <form id="comment-form" novalidate>
                <div class="form-row">
                    <div class="form-group col-sm-7">
                        <label for="name">Email</label>
                        <input type="email" class="form-control" id="name" name="name"
                               placeholder="user@mail.ru" maxlength="255">
                        <div class="invalid-feedback" data-error-for="name"></div>
                    </div>
                    <div class="form-group col-sm-5">
                        <label for="date">Дата</label>
                        <input type="date" class="form-control" id="date" name="date">
                        <div class="invalid-feedback" data-error-for="date"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="text">Комментарий</label>
                    <textarea class="form-control" id="text" name="text" rows="3" maxlength="5000"></textarea>
                    <div class="invalid-feedback" data-error-for="text"></div>
                </div>

                <button type="submit" class="btn btn-primary">Отправить</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?= base_url('js/comments.js') ?>"></script>
</body>
</html>