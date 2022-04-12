<?php

/**
 * @param string $fileName имя файла
 * @return string результат валидации
 */
function dataValidation(string $fileName): string
{
    // открытие файла с входными данными для чтения
    $input = fopen($fileName, 'r');
    // строка ответа
    $result = '';
    while ($str = fgets($input)) {
        // находим все параметры ввода строку и необходимый тип и параметры валидации
        $line = '';
        $tmp = '';
        for ($i = 0; $i < strlen($str); $i++) {
            if ($str[$i] == '<') {
                $i++;
                while ($str[$i] != '>') {
                    $line .= $str[$i];
                    $i++;
                }
                $i++;
                continue;
            }
            $tmp .= $str[$i];
        }
        $array = explode(' ', $tmp);
        $array[0] = trim($array[0]);
        // сравниваю длину строк с параметрами по условию
        if ($array[0] == 'S') {
            if (strlen($line) >= $array[1] && strlen($line) <= $array[2]) {
                $result .= "OK\n";
            } else {
                $result .= "FAIL\n";
            }
        }
        // проверяю, что либо положительное, либо отрицательное, либо ноль и сравнениваю с условиями -- иначе fail
        if ($array[0] == 'N') {
            if (preg_match('/^-[0-9]*[1-9][0-9]*$/', $line) || preg_match('/^[0-9]*[1-9][0-9]*$/', $line) || $line == '0') {
                if (intval($array[1]) <= intval($line) && intval($array[2]) >= intval($line)) {
                    $result .= "OK\n";
                } else {
                    $result .= "FAIL\n";
                }
            } else {
                $result .= "FAIL\n";
            }
        }

        if ($array[0] == 'P') {
            if (strlen($line) == 18 && preg_match("/\+7 \([0-9]{3}\) ([0-9]{3})-([0-9]{2})-([0-9]{2})/", $line)) {
                $result .= "OK\n";
            } else {
                $result .= "FAIL\n";
            }
        }

        if ($array[0] == 'D') {
            $dateAndTime = explode(" ", $line);
            $date = $dateAndTime[0];

            // значение d-m-y в отдельные переменные
            $day = explode(".", $date)[0];
            $month = explode(".", $date)[1];
            $year = explode(".", $date)[2];
            // проверяем существование даты
            $isDateValid = checkdate($month, $day, $year);
            // год должен быть 4-х символьным
            if (strlen($year) != 4) {
                $isDateValid = false;
            }

            $time = $dateAndTime[1];
            $hours = explode(":", $time)[0];
            $minutes = explode(":", $time)[1];


            if ($hours < 24) {
                $isHoursValid = true;
            } else {
                $isHoursValid = false;
            }

            if ($minutes < 60) {
                $isMinutesValid = true;
            } else {
                $isMinutesValid = false;
            }
            // если все условия соблюдаются
            if ($isDateValid && $isHoursValid && $isMinutesValid) {
                $result .= "OK\n";
            } else {
                $result .= "FAIL\n";
            }
        }
        if ($array[0] == 'E') {
            if ($line[0] != '_') {
                if (preg_match('/([0]{0})([A-Za-z0-9]{1})([A-Za-z0-9_]{3,29})@([A-Za-z]{2,30})[.]([a-z]{2,10})/', $line)) {
                    $result .= "OK\n";
                } else {
                    $result .= "FAIL\n";
                }
            } else {
                $result .= "FAIL\n";
            }
        }
    }
    return $result;
}


// получение массива с файлами тестов
$data = glob('C/*.dat');
// получение массива с файлами ответов на тесты
$ans = glob('C/*.ans');

echo "Tests:" . "<br>";

for ($i = 0; $i < 14; $i++) {
    // получение результатов вычислений
    $result = dataValidation($data[$i]);
    // получение ответов
    $answer = file_get_contents($ans[$i], 'r');
    echo 'Test ' . ($i + 1) . ': ';

    if ($answer == $result) {
        echo 'OK<br>';
    } else {
        echo 'WA. Errors in Test №' . ($i + 1) . '<br>';
        echo $answer . '<br>' . $result . '<br>';
    }
}
