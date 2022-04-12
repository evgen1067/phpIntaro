<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet"
          href="css/bootstrap.min.css">
    <link rel="stylesheet"
          href="css/main.css">
</head>
<body>

<div class="container d-flex flex-column align-content-center">
    <h3 class="text-center">Форма обратной связи</h3>
    <form id="feedbackForm">
        <div class="mb-3">
            <label for="inputName" class="form-label">
                Ваше ФИО
            </label>
            <input name="name" type="text" class="form-control" id="inputName" aria-describedby="nameHelp">
            <div id="nameHelp" class="form-text">
                Мы никогда не поделимся вашими данными с кем-либо еще.
            </div>
        </div>
        <div class="mb-3">
            <label for="inputEmail" class="form-label">
                Ваш Email
            </label>
            <input name="email" type="text" class="form-control" id="inputEmail" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">
                Мы никогда не поделимся вашей электронной почтой с кем-либо еще.
            </div>
        </div>
        <div class="mb-3">
            <label for="inputPhone" class="form-label">
                Ваш телефон
            </label>
            <input name="tel" type="text" class="form-control" id="inputPhone" aria-describedby="telHelp">
            <div id="telHelp" class="form-text">
                Мы никогда не поделимся вашим телефоном с кем-либо еще.
            </div>
        </div>
        <div class="mb-3">
            <label for="commentTextarea" class="form-label">Ваш комментарий</label>
            <textarea class="form-control" id="commentTextarea"  aria-describedby="commentHelp"></textarea>
            <div id="commentHelp" class="form-text">
                Мы никогда не поделимся вашим сообщением с кем-либо еще.
            </div>
        </div>
        <div class="mb-3">
            <input type="submit" class="btn btn-primary" value="Отправить">
        </div>
    </form>
</div>

<div id="modalAlert" class="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Обратная связь.</h5>
                <button type="button" class="btn-close collapse-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Оставлено сообщение из формы обратной связи.</p>
                <p id="nameAlert">Имя: .</p>
                <p id="emailAlert">Email: .</p>
                <p id="telAlert">Телефон: .</p>
                <p id="commentAlert">Сообщение: .</p>
                <p id="dateAlert">Сообщение: .</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary collapse-btn" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript"
        src="js/JQuery.js"></script>
<script type="text/javascript"
        src="js/bootstrap.bundle.js"></script>
<script type="text/javascript"
        src="js/main.js"></script>
</body>
</html>
