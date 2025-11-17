<?php
// Forzar salida en UTF-8
header("Content-Type: text/html; charset=UTF-8");

// Cargar JSON de películas en UTF-8
$json = file_get_contents("peliculas.json");

// Si el JSON no está en UTF-8, lo convertimos
$json = mb_convert_encoding($json, 'UTF-8', 'auto');

$movies = json_decode($json, true);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recomendador RBM</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .movie-card {
            background: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .star { 
            color: gold; 
            font-size: 20px; 
            cursor: pointer; 
        }
    </style>
</head>

<body class="bg-light">

<div class="container py-4">
    <h2 class="text-center mb-4">⭐ Califica al menos 5 películas ⭐</h2>

    <form action="procesar_recomendacion.php" method="POST">

        <?php foreach ($movies as $movie): ?>
            <div class="movie-card">

                <h5><?= htmlspecialchars($movie["Title"], ENT_QUOTES, 'UTF-8') ?></h5>
                <p class="text-muted"><?= htmlspecialchars($movie["Genres"], ENT_QUOTES, 'UTF-8') ?></p>

                <?php for ($r = 1; $r <= 5; $r++): ?>
                    <label class="me-2">
                        <input type="radio" name="rating[<?= $movie["MovieID"] ?>]" value="<?= $r ?>">
                        <span class="star">★</span> <?= $r ?>
                    </label>
                <?php endfor; ?>

            </div>
        <?php endforeach; ?>

        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-lg mt-3">Obtener Recomendaciones 🎬</button>
        </div>

    </form>
</div>

</body>
</html>