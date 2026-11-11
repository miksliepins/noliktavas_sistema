<?php
require_once 'includes/auth.php';
require_login();
require_once 'includes/layout.php';

$tasks = [
    'administrators' => [
        'Izveidot, rediģēt un dzēst lietotāju kontus',
        'Piešķirt un mainīt lietotāju lomas un piekļuves tiesības',
        'Pievienot, rediģēt un dzēst noliktavas datus',
        'Apskatīt un ģenerēt pārskatus par noliktavas darbību',
    ],
    'noliktavas_darbinieks' => [
        'Pievienot, rediģēt un atjaunināt preču informāciju',
        'Izveidot un pārvaldīt pasūtījumus',
        'Mainīt pasūtījumu statusus atbilstoši izpildes procesam',
        'Apskatīt un ģenerēt pārskatus par noliktavas darbību',
    ],
    'plauktu_kartotajs' => [
        'Apskatīt informāciju par noliktavā esošajām precēm',
        'Reģistrēt preču izvietojumu un atrašanās vietu',
        'Atjaunināt informāciju par izvietojumu un veiktajām izmaiņām',
        'Sagatavot pārskatus par preču izvietojumu un stāvokli',
    ],
];

page_start('Sākums');
?>
<div class="card">
    <h2>Laipni lūdzam, <?= esc($_SESSION['lietotajvards']) ?>!</h2>
    <p class="muted">Jūsu loma: <strong><?= esc(ROLES[$_SESSION['loma']]) ?></strong></p>
</div>

<div class="card">
    <h2>Jūsu iespējas</h2>
    <ul>
        <?php foreach ($tasks[$_SESSION['loma']] as $task): ?>
            <li><?= esc($task) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php page_end(); ?>