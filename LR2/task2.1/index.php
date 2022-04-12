<?php
/**
 *
 * @param string $fileName имя файла
 * @return string правильный ip-адрес
 */
function convertToFullAddress(string $fileName): string
{
    // чтение из файла
    $file = fopen($fileName, 'r');
    $result = '';
    for ($j = 0; $j < count(file($fileName)); $j++) {
        // делим блоки по двоеточиям
        $arr = explode(":", fgets($file));
        // прогонка по блокам
        for ($i = 0; $i < count($arr); $i++) {
            // чистка пробелов
            $arr[$i] = trim($arr[$i]);
            // если встречается ::, заполняем нулями до макс. кол-ва блоков
            if ($arr[$i] == '') {
                while (count($arr) < 8) {
                    $key = $i;
                    array_splice($arr, $key, 0, '0000');
                }
            }
        }
        // если блоков < 8 дополняем нулями справа
        while (count($arr) < 8) {
            $arr[] = '0000';
        }
        // если длина блока < 4, то дополняем слева нулями
        for ($i = 0; $i < 8; $i++) {
            if (strlen($arr[$i]) < 4) {
                $arr[$i] = str_pad($arr[$i], 4, '0', STR_PAD_LEFT);
            }
        }
        // записываем все результаты
        $result .= implode(':', $arr) . "\n";
    }
    return $result;
}

// получение массива с файлами тестов
$data = glob('B/*.dat');
// получение массива с файлами ответов на тесты
$ans = glob('B/*.ans');

echo "Tests: <br><br>";

for ($i = 0; $i < 8; $i++) {
    // получение результатов вычислений
    $result = convertToFullAddress($data[$i]);
    // получение ответов
    $answer = file_get_contents($ans[$i], 'r');
    echo 'Test ' . ($i + 1) . ': ';

    if ($answer == $result) {
        echo 'OK<br>';
    } else {
        echo 'WA. Errors in Test №' . ($i + 1) . '<br>';
    }
}
