<?php

/**
 * @param string $fileName (имя файла)
 * @return int $balance (итоговый баланс)
 */
function calculate(string $fileName): int
{
    // открытие файла с входными данными для чтения
    $input = fopen($fileName, 'r');
    // число сделаных ставок
    $n = fgets($input);
    $bets = array();
    // баланс участника
    $balance = 0;
    // прогонка по всем ставкам
    for ($i = 0; $i < $n; $i++) {
        // ai — это идентификатор игры, si — сумма ставки на исход игры, ri — результат игры
        list($a, $s, $r) = explode(" ", fgets($input));
        $r = trim($r);
        $bets[$a][$r]=$s;
        // ставка сделаана => баланс уменьшен
        $balance -=$s;
    }
    // число проведённых игр
    $m = fgets($input);
    // прогонка по всем играм
    for ($i = 0; $i < $m; $i++) {
        // ai — это идентификатор игры, cj — коэффициент на победу левой команды, dj — коэффициент на победу правой команды,
        // kj — коэффициент на ничью, rj — результат игры
        list($a, $c, $d, $k, $r) = explode(" ", fgets($input));
        $r = trim($r);
        //ставка с индексом ai с результатом ri - если есть, заходим в if
        if (isset($bets[$a][$r])) {
            // вычисление выигрыша
            $winning = $bets[$a][$r];
            if ($r == 'L') {
                // победа левой команды
                $winning *= $c;
            } elseif ($r == 'R') {
                // победа правой команды
                $winning *= $d;
            } else {
                // ничья
                $winning *= $k;
            }
            $balance += $winning;
        }
    }
    // возвращаем итоговый баланс
    return $balance;
}

// получение массива с файлами тестов
$inputData = glob('A/*.dat');
// получение массива с файлами ответов на тесты
$inputAns = glob('A/*.ans');

echo "Результаты тестов: <br><br>";

// прогонка по всем инпутам и сравнение ответа алгоритма с верным ответом
for ($i = 0; $i < sizeof($inputData); $i++) {
    // открытие файла с ответами для чтения
    $output = fopen($inputAns[$i], 'r');

    // получение результатов моего алгоритма
    $result = calculate($inputData[$i]);

    // записываем верный ответ из файла в переменнуб
    $answer = fgets($output);

    echo($i + 1) . '. ';

    if ($answer == $result) {
        echo 'ОК<br>';
    } else {
        echo 'WA<br>Your answer: ' . $result . '<br>Correct answer: ' . $answer . '<br>';
    }
}
