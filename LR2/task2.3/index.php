<?php
/**
 * @param string $str начальная строка
 * @return string полученная строка
 */
function increaseNumbers(string $str): string
{
    $result = '';
    preg_match_all("/[']+[0-9]+[']/", $str, $num_array);
    for ($i = 0; $i < count($num_array[0]); $i++) {
        $num_array[0][$i] = "'" . trim($num_array[0][$i], "'") * 2 . "'";
    }

    // обратный цикл с заменой значений в кавычках
    for ($i = count($num_array[0]) - 1; $i >= 0; $i--) {
        $count = $i + 1;
        $str = preg_replace("/['][0-9]+[']/", $num_array[0][$i], $str, $count);
    }
    return $str;
}

echo 'Начальная строка: ' . $_GET['str'] . '<br><br>';

echo 'Полученная строка: ' . increaseNumbers($_GET['str']);
