
$(function () {

    var state = { page: 1, sort: 'id', order: 'desc' };

    var EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

    var $list       = $('#comments-list');
    var $pagination = $('#pagination');
    var $form       = $('#comment-form');

    function today() {
        var d = new Date();
        var month = String(d.getMonth() + 1).padStart(2, '0');
        var day   = String(d.getDate()).padStart(2, '0');
        return d.getFullYear() + '-' + month + '-' + day;
    }

    function formatDate(value) {
        return value.split('-').reverse().join('.');
    }

    function showAlert(message, type) {
        var $alert = $('#alert');
        $alert.removeClass('d-none alert-success alert-danger')
              .addClass('alert-' + (type || 'danger'))
              .text(message);
        clearTimeout(showAlert.timer);
        showAlert.timer = setTimeout(function () { $alert.addClass('d-none'); }, 4000);
    }

    function loadComments() {
        $.getJSON('/comments', state)
            .done(function (data) {
                state.page = data.page;
                renderComments(data.comments);
                renderPagination(data.pages);
            })
            .fail(function () {
                showAlert('Не удалось загрузить комментарии.');
            });
    }

    function renderComments(comments) {
        $list.empty();

        if (comments.length === 0) {
            $list.append($('<p class="text-muted text-center my-4">').text('Комментариев пока нет. Будьте первым!'));
            return;
        }

        $.each(comments, function (i, c) {
            var $meta = $('<div class="d-flex flex-wrap align-items-baseline mb-2">').append(
                $('<strong class="mr-2 text-break">').text(c.name),
                $('<small class="text-muted">').text(formatDate(c.date) + ' · #' + c.id)
            );

            var $deleteBtn = $('<button type="button" class="btn btn-sm btn-outline-danger ml-2 js-delete">')
                .text('Удалить')
                .attr('data-id', c.id);

            var $card = $('<div class="card mb-3">').append(
                $('<div class="card-body">').append(
                    $('<div class="d-flex justify-content-between align-items-start">').append($meta, $deleteBtn),
                    $('<p class="card-text comment-text mb-0">').text(c.text)
                )
            );

            $list.append($card);
        });
    }

    function renderPagination(pages) {
        $pagination.empty();
        if (pages <= 1) {
            return;
        }

        function pageItem(label, page, disabled, active) {
            return $('<li class="page-item">')
                .toggleClass('disabled', disabled)
                .toggleClass('active', active)
                .append($('<a class="page-link" href="#">').text(label).attr('data-page', page));
        }

        $pagination.append(pageItem('«', state.page - 1, state.page === 1, false));
        for (var p = 1; p <= pages; p++) {
            $pagination.append(pageItem(p, p, false, p === state.page));
        }
        $pagination.append(pageItem('»', state.page + 1, state.page === pages, false));
    }

    function setError(field, message) {
        $form.find('[name="' + field + '"]').toggleClass('is-invalid', !!message);
        $form.find('[data-error-for="' + field + '"]').text(message || '');
    }

    function validateEmail() {
        var value = $.trim($('#name').val());
        if (value === '') {
            setError('name', 'Укажите email.');
            return false;
        }
        if (!EMAIL_RE.test(value)) {
            setError('name', 'Введите корректный email, например user@mail.ru.');
            return false;
        }
        setError('name', '');
        return true;
    }

    function validateRequired(field, message) {
        var ok = $.trim($form.find('[name="' + field + '"]').val()) !== '';
        setError(field, ok ? '' : message);
        return ok;
    }

    function validateForm() {
        var ok = validateEmail();
        ok = validateRequired('date', 'Укажите дату.') && ok;
        ok = validateRequired('text', 'Введите текст комментария.') && ok;
        return ok;
    }

    $('#sort, #order').on('change', function () {
        state.sort  = $('#sort').val();
        state.order = $('#order').val();
        state.page  = 1;
        loadComments();
    });

    $pagination.on('click', 'a', function (e) {
        e.preventDefault();
        var $li = $(this).parent();
        if ($li.hasClass('disabled') || $li.hasClass('active')) {
            return;
        }
        state.page = Number($(this).attr('data-page'));
        loadComments();
    });

    $list.on('click', '.js-delete', function () {
        if (!confirm('Удалить этот комментарий?')) {
            return;
        }
        var $btn = $(this).prop('disabled', true);

        $.ajax({
            url: '/comments/' + $btn.attr('data-id'),
            type: 'DELETE',
            dataType: 'json'
        })
            .done(function () {
                showAlert('Комментарий удалён.', 'success');
                loadComments();
            })
            .fail(function () {
                $btn.prop('disabled', false);
                showAlert('Не удалось удалить комментарий.');
            });
    });

    $('#name').on('blur', validateEmail);
    $('#name').on('input', function () {
        if ($(this).hasClass('is-invalid')) {
            validateEmail();
        }
    });

    $('#text, #date').on('input change', function () {
        setError(this.name, '');
    });

    $form.on('submit', function (e) {
        e.preventDefault(); 

        if (!validateForm()) {
            return;
        }

        var $btn = $form.find('[type="submit"]').prop('disabled', true);

        $.post('/comments', $form.serialize())
            .done(function () {
                $form[0].reset();
                $('#date').val(today());
                showAlert('Комментарий добавлен.', 'success');

                if (state.sort === 'id') {
                    state.page = state.order === 'desc' ? 1 : 1000000;
                }
                loadComments();
            })
            .fail(function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON) {
                    $.each(xhr.responseJSON.errors, setError);
                } else {
                    showAlert('Не удалось добавить комментарий.');
                }
            })
            .always(function () {
                $btn.prop('disabled', false);
            });
    });


    $('#date').val(today()); 
    loadComments();   
});