<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Интересные соотношения</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .container {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        form {
            margin-bottom: 20px;
        }
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
            min-height: 100px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            background-color: #e9f7ef;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Интересные соотношения</h1>
        
        <form method="post">
            <label for="input_data">Введите входные данные:</label><br>
            <textarea id="input_data" name="input_data" required><?php 
                echo isset($_POST['input_data']) ? htmlspecialchars($_POST['input_data']) : "3\n2\n3\n5"; 
            ?></textarea><br><br>
            <button type="submit">Рассчитать</button>
        </form>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input_data = trim($_POST['input_data']);
            $lines = explode("\n", $input_data);
            
            // Проверка корректности ввода
            if (count($lines) < 1 || !is_numeric($lines[0])) {
                echo '<div class="result">Ошибка: Неверный формат входных данных</div>';
                exit;
            }
            
            $t = (int)$lines[0];
            if ($t < 1 || $t > 1000) {
                echo '<div class="result">Ошибка: Количество тестов должно быть от 1 до 1000</div>';
                exit;
            }
            
            if (count($lines) !== $t + 1) {
                echo '<div class="result">Ошибка: Неверное количество входных значений</div>';
                exit;
            }
            
            $queries = [];
            $max_n = 0;
            for ($i = 1; $i <= $t; $i++) {
                $n = (int)$lines[$i];
                if ($n < 2 || $n > 10000000) {
                    echo '<div class="result">Ошибка: Значение n должно быть от 2 до 10,000,000</div>';
                    exit;
                }
                $queries[] = $n;
                if ($n > $max_n) {
                    $max_n = $n;
                }
            }
            
            // Вычисление простых чисел до max_n с помощью решета Эратосфена
            function precompute_primes($max_limit) {
                $sieve = array_fill(0, $max_limit + 1, true);
                $sieve[0] = $sieve[1] = false;
                for ($i = 2; $i * $i <= $max_limit; $i++) {
                    if ($sieve[$i]) {
                        for ($j = $i * $i; $j <= $max_limit; $j += $i) {
                            $sieve[$j] = false;
                        }
                    }
                }
                $primes = [];
                for ($i = 2; $i <= $max_limit; $i++) {
                    if ($sieve[$i]) {
                        $primes[] = $i;
                    }
                }
                return $primes;
            }
            
            $primes = precompute_primes($max_n);
            
            // Вычисление результатов для каждого запроса
            $results = [];
            foreach ($queries as $n) {
                $res = 0;
                foreach ($primes as $p) {
                    if ($p > $n) {
                        break;
                    }
                    $res += intdiv($n, $p);
                }
                $results[] = $res;
            }
            
            // Вывод результатов
            echo '<div class="result">';
            echo '<h3>Результаты:</h3>';
            echo '<table>';
            echo '<tr><th>№</th><th>n</th><th>Количество пар</th></tr>';
            for ($i = 0; $i < $t; $i++) {
                echo '<tr>';
                echo '<td>' . ($i + 1) . '</td>';
                echo '<td>' . $queries[$i] . '</td>';
                echo '<td>' . $results[$i] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>