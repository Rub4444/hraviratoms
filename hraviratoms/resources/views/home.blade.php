<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Навбар -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Мой Сайт</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">Главная</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">О нас</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Контакты</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Контент -->
    <div class="container my-5">
        <div class="text-center">
            <h1 class="mb-4">Добро пожаловать на главную страницу!</h1>
            <p class="lead">Это простая страница Laravel с дизайном на Bootstrap.</p>
            <a href="#" class="btn btn-primary mt-3">Узнать больше</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        &copy; 2025 Мой Сайт. Все права защищены.
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
