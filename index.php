<?php

ini_set('display_errors', 1);

require_once 'vendor/autoload.php';

require 'src/application.php';

Use App\Application;

$application = new Application();
$application->run();

// connexion mysql avec pdo
$config = parse_ini_file(__DIR__.'/config.ini', true);

try {
    $pdo = new PDO('mysql:host='.$config['DB_HOST'].';dbname='.$config['DB_NAME'], $config['DB_USER'], $config['DB_PASSWORD'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]);
} catch (Exception $e) {
    die('Erreur de connexion....' . $e->getMessage());
}

// je récupère la liste des thread
$sql = 'SELECT * FROM `thread` ORDER BY createdAt DESC;';
$sth = $pdo->prepare($sql);
$sth->execute();
$threads = $sth->fetchAll(PDO::FETCH_ASSOC);
foreach($threads as $key => $thread) {
    $date = new DateTime($thread['createdAt']);
    $threads[$key]['createdAt'] = $date->format('d/m/Y à H:i');
}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Forum PHP</title>
  </head>
  <body>
    <h1>Mon forum en PHP</h1>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">Sujet</th>
                <th scope="col">Auteur</th>
                <th scope="col">Dernier post</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($threads as $thread): ?>
                <tr>
                    <td>
                        <a href="">
                            <?= $thread['subject'] ?>
                        </a>
                    </td>
                    <td>
                        <a href="">
                            <?= $thread['author'] ?>
                        </a>    
                    </td>
                    <td><?= $thread['createdAt'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>