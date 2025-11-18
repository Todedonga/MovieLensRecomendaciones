<?php
// Forzar salida en UTF-8
header("Content-Type: text/html; charset=UTF-8");

// Cargar JSON de películas en UTF-8
$json = file_get_contents("peliculas.json");

// Si el JSON no está en UTF-8, lo convertimos
$json = mb_convert_encoding($json, 'UTF-8', 'auto');

$movies = json_decode($json, true);

// Seleccionar aleatoriamente solo 10 películas
shuffle($movies);
$movies = array_slice($movies, 0, 10);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recomendador RBM</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .header-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .movies-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .movie-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .movie-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }

        .movie-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            line-height: 1.3;
            min-height: 40px;
        }

        .movie-genres {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 15px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e9ecef;
        }

        .rating-container {
            display: flex;
            justify-content: space-between;
            gap: 5px;
            margin-top: auto;
        }

        .rating-label {
            flex: 1;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .rating-label input[type="radio"] {
            display: none;
        }

        .star {
            color: #ddd;
            font-size: 24px;
            transition: all 0.2s ease;
            display: block;
        }

        .rating-label:hover .star {
            color: #ffc107;
            transform: scale(1.2);
        }

        .rating-label input[type="radio"]:checked ~ .star {
            color: #ff9800;
        }

        .rating-number {
            font-size: 11px;
            color: #6c757d;
            margin-top: 3px;
        }

        .submit-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
        }

        .btn-recommend {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 15px 50px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-recommend:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        @media (max-width: 768px) {
            .movies-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 15px;
            }
        }
    </style>
</head>

<body>

<div class="container py-4">
    <div class="header-section">
        <h1 class="text-center mb-2">Recomendador de Películas</h1>
        <p class="text-center text-muted mb-0">Califica al menos 5 películas para obtener recomendaciones personalizadas
        </p>
    </div>

    <form action="procesar_recomendacion.php" method="POST">

        <div class="movies-grid">
            <?php foreach ($movies as $movie): ?>
                <div class="movie-card">
                    <div class="movie-title"><?= htmlspecialchars($movie["Title"], ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="movie-genres"><?= htmlspecialchars($movie["Genres"], ENT_QUOTES, 'UTF-8') ?></div>

                    <div class="rating-container">
                        <?php for ($r = 1; $r <= 5; $r++): ?>
                            <label class="rating-label">
                                <input type="radio" name="rating[<?= $movie["MovieID"] ?>]" value="<?= $r ?>">
                                <span class="star">★</span>
                                <div class="rating-number"><?= $r ?></div>
                            </label>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="submit-section">
            <button type="submit" class="btn btn-primary btn-recommend">
                Obtener Recomendaciones
            </button>
        </div>

    </form>
</div>

<script>
    // Mejorar la interacción de las estrellas
    document.querySelectorAll('.rating-container').forEach(container => {
        const labels = container.querySelectorAll('.rating-label');

        labels.forEach((label, index) => {
            label.addEventListener('mouseenter', () => {
                labels.forEach((l, i) => {
                    if (i <= index) {
                        l.querySelector('.star').style.color = '#ffc107';
                    } else {
                        const radio = l.querySelector('input[type="radio"]');
                        if (!radio.checked) {
                            l.querySelector('.star').style.color = '#ddd';
                        }
                    }
                });
            });
        });

        container.addEventListener('mouseleave', () => {
            labels.forEach(label => {
                const radio = label.querySelector('input[type="radio"]');
                const star = label.querySelector('.star');
                if (!radio.checked) {
                    star.style.color = '#ddd';
                } else {
                    star.style.color = '#ff9800';
                }
            });
        });
    });
</script>

</body>
</html>