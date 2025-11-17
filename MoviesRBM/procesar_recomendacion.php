<?php

if (!isset($_POST["rating"])) {
    die("No enviaste calificaciones.");
}

$ratings = $_POST["rating"];

// Debe calificar mínimo 5
if (count($ratings) < 5) {
    echo "<script>alert('Debes calificar al menos 5 películas'); window.history.back();</script>";
    exit;
}

// -------------------------------------------
// Convertir formato a diccionario:
// {"1655":5, "1681":4}
// -------------------------------------------
$ratings_dict = [];

foreach ($ratings as $movieID => $rating) {
    $ratings_dict[strval($movieID)] = intval($rating);
}

// Guardar JSON EXACTO que Python espera
$json_path = "user_ratings.json";
file_put_contents(
    $json_path,
    json_encode($ratings_dict, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
);

// Ejecutar Python
$cmd = "python rbm_recommender.py $json_path 2>&1";
$output = shell_exec($cmd);

// Validar output
if (!$output) {
    die("Python no regresó nada.");
}

// Parsear JSON
$recommendations = json_decode($output, true);

if (!$recommendations) {
    die("Python regresó salida inválida:<br><pre>$output</pre>");
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recomendaciones RBM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <h2 class="text-center mb-4">🎬 Tus Recomendaciones</h2>

    <?php foreach ($recommendations as $movie): ?>
        <div class="card p-3 mb-3 shadow">
            <h4><?= htmlspecialchars($movie["title"], ENT_QUOTES, "UTF-8") ?></h4>
            <p class="text-muted"><?= htmlspecialchars($movie["genres"], ENT_QUOTES, "UTF-8") ?></p>
            <p><strong>Motivo:</strong> <?= htmlspecialchars($movie["explanation"], ENT_QUOTES, "UTF-8") ?></p>
        </div>
    <?php endforeach; ?>

    <div class="text-center mt-3">
        <a href="recomendar.php" class="btn btn-secondary">Volver</a>
    </div>
</div>

</body>
</html>