<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LR2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<style>
    * {
        font-size: 18px;
    }

    .container {
        min-height: 90vh;
    }
</style>
<body>

<div class="container d-flex align-items-center justify-content-center align-content-center flex-column">
    <h3 class="mb-3">Лабораторная работа № 2</h3>
    <a href="task2.1/index.php" class="btn btn-primary mb-3">Задание 2.1</a>
    <a href="task2.2/index.php" class="btn btn-primary">Задание 2.2</a>
    <form method="get" action="task2.3/index.php" class="mb-3">
        <label for="inputTask2_3" class="form-label">Строка</label>
        <input name="str" type="text" id="inputTask2_3" class="form-control" aria-describedby="inputHelpTask2_3">
        <div id="inputHelpTask2_3" class="form-text mb-3">
            Введите строку с целыми числами. Будут найдены числа,
            стоящие в кавычках и увеличены в два раза.
            Пример: из строки 2aaa'3'bbb'4' будет получена строку
            2aaa'6'bbb'8'.
        </div>
        <input type="submit" class="btn btn-primary" value="Задание 2.3 (Отправить)">
    </form>
    <form method="get" action="task2.4/index.php">
        <label for="inputTask2_4" class="form-label">Ссылка</label>
        <input name="str" type="text" id="inputTask2_4" class="form-control" aria-describedby="inputHelpTask2_4">
        <div id="inputHelpTask2_4" class="form-text">
            Введите ссылку вида:
            <div class="highlight">
                <pre tabindex="0" class="chroma">
                    <code class="language-sh" data-lang="sh">
                        http://asozd.duma.gov.ru/main.nsf/(Spravka)?OpenAgent&RN=<номер законопроекта>&<целое число>
                    </code>
                </pre>
            </div>
            Полученная ссылка будет иметь формат:
            <div class="highlight">
                <pre tabindex="0" class="chroma">
                    <code class="language-sh" data-lang="sh">
                        http://sozd.parlament.gov.ru/bill/<номер законопроекта>
                    </code>
                </pre>
            </div>
        </div>
        <input type="submit" class="btn btn-primary" value="Задание 2.3 (Отправить)">
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js">
</script>
</body>
</html>