<?php
function nav_links(): array {
    $links = [['panelis.php', 'Sākums'], ['preces.php', 'Preces']];

    if (has_role(['administrators'])) {
        $links[] = ['lietotaji.php', 'Lietotāji'];
    }
    if (has_role(['administrators', 'noliktavas_darbinieks'])) {
        $links[] = ['pasutijumi.php', 'Pasūtījumi'];
    }
    if (has_role(['administrators', 'plauktu_kartotajs'])) {
        $links[] = ['izvietojums.php', 'Izvietojums'];
    }
    $links[] = ['parskati.php', 'Pārskati'];

    return $links;
}

function page_start(string $title): void { ?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?> · Noliktava</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="topbar">
    <a class="brand" href="panelis.php">Noliktava</a>
    <nav>
        <?php foreach (nav_links() as [$href, $label]): ?>
            <a href="<?= $href ?>"><?= $label ?></a>
        <?php endforeach; ?>
    </nav>
    <div class="user">
        <?= esc($_SESSION['lietotajvards']) ?> · <?= esc(ROLES[$_SESSION['loma']]) ?>
        · <a href="logout.php">Iziet</a>
    </div>
</header>
<main class="container">
<?php if (!empty($_SESSION['flash'])):
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']); ?>
    <div class="alert <?= esc($f['type']) ?>"><?= esc($f['msg']) ?></div>
<?php endif;
}

function page_end(): void { ?>
</main>
<script src="app.js" defer></script>
</body>
</html>
<?php }