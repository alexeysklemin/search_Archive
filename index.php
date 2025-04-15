<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$DB_HOST = 'localhost';
$DB_USER = 'www-data'; 
$DB_PASSWORD = 'some_password';
$DB_NAME = 'archive';

try {
    $db = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

function searchArchives(PDO $db, string $query, int $limit = 50): array {
    $searchTerm = "%$query%";
    
    $sql = "SELECT 
                inv.inv_name AS inventory_name,
                inv.inv_address AS inventory_address,
                inv.inv_description AS inventory_description,
                f.fond_name,
                f.fond_addtess AS fond_address,
                f.fond_description,
                sp.spec_name AS species_name,
                sp.spec_address AS species_address,
                sp.spec_description AS species_description,
                ty.typ_name AS type_name,
                ty.typ_address AS type_address,
                ty.typ_description AS type_description
            FROM inventories inv
            LEFT JOIN founds f ON inv.fond_id = f.fond_id
            LEFT JOIN species sp ON f.spec_id = sp.spec_id
            LEFT JOIN types ty ON sp.typ_id = ty.typ_id
            WHERE 
                inv.inv_name LIKE :query OR
                inv.inv_description LIKE :query OR
                f.fond_name LIKE :query OR
                f.fond_description LIKE :query OR
                sp.spec_name LIKE :query OR
                sp.spec_description LIKE :query OR
                ty.typ_name LIKE :query OR
                ty.typ_description LIKE :query
            ORDER BY 
                CASE 
                    WHEN inv.inv_name LIKE :query THEN 1
                    WHEN f.fond_name LIKE :query THEN 2
                    WHEN sp.spec_name LIKE :query THEN 3
                    WHEN ty.typ_name LIKE :query THEN 4
                    ELSE 5
                END
            LIMIT :limit";
    
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':query', $searchTerm);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

if(isset($_GET['query']) && !empty(trim($_GET['query']))) {
    $query = trim($_GET['query']);
    $results = searchArchives($db, $query);
    
    if(!empty($results)) {
        echo '<h2>Результаты поиска:</h2>';
        echo '<div class="search-results">';
        
        foreach($results as $item) {
            echo '<div class="archive-item">';
            
            // Основная информация об описи
            echo '<h3>' . htmlspecialchars($item['inventory_name']) . '</h3>';
            if(!empty($item['inventory_description'])) {
                echo '<div class="description">' . nl2br(htmlspecialchars($item['inventory_description'])) . '</div>';
            }
            
            // Информация о фонде
            if(!empty($item['fond_name'])) {
                echo '<div class="fond-info">';
                echo '<h4>Фонд: ' . htmlspecialchars($item['fond_name']) . '</h4>';
                if(!empty($item['fond_description'])) {
                    echo '<p>' . nl2br(htmlspecialchars($item['fond_description'])) . '</p>';
                }
                echo '</div>';
            }
            
            // Информация о типе фонда
            if(!empty($item['species_name'])) {
                echo '<div class="species-info">';
                echo '<h5>Тип фонда: ' . htmlspecialchars($item['species_name']) . '</h5>';
                if(!empty($item['species_description'])) {
                    echo '<p>' . nl2br(htmlspecialchars($item['species_description'])) . '</p>';
                }
                echo '</div>';
            }
            
            // Информация о виде фонда
            if(!empty($item['type_name'])) {
                echo '<div class="type-info">';
                echo '<h5>Вид фонда: ' . htmlspecialchars($item['type_name']) . '</h5>';
                if(!empty($item['type_description'])) {
                    echo '<p>' . nl2br(htmlspecialchars($item['type_description'])) . '</p>';
                }
                echo '</div>';
            }
            
            echo '</div><hr>';
        }
        
        echo '</div>';
    } else {
        echo '<p>По вашему запросу "'.htmlspecialchars($query).'" ничего не найдено.</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Архивный поиск</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .search-form {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #2980b9;
        }
        .search-results {
            margin-top: 30px;
        }
        .archive-item {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .archive-item h3 {
            color: #2c3e50;
            margin-top: 0;
        }
        .archive-item h4 {
            color: #3498db;
            margin-bottom: 5px;
        }
        .archive-item h5 {
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        .description, .fond-info, .species-info, .type-info {
            margin-bottom: 15px;
        }
        hr {
            border: 0;
            height: 1px;
            background: #eee;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h1>Поиск по архивным описям</h1>
    
    <div class="search-form">
        <form action="" method="get">
            <input type="text" name="query" placeholder="Введите название описи, фонда или тип..." 
                   value="<?= isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '' ?>">
            <button type="submit">Найти</button>
        </form>
    </div>
</body>
</html>
