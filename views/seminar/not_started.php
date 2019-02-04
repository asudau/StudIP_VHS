
<div style='width:50%;margin:auto'>
    <div style = 'display:flex; justify-content: space-between; margin-left: 20px;'>
    <?= Icon::create('date','clickable', ['size' => 100])?>
    <h1 style='margin-top:20px'>
        Der Zugriff auf diesen Kurs ist ab dem <?= date('d.m.Y', $coursebegin) ?> möglich
    </h1>
    </div>
</div>