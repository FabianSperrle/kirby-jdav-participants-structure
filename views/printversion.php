<html>
    <head>
        <meta charset='UTF-8'>
        <title>Druckversion</title>
    </head>
    <body>
        <table border='1px' cellspacing='0' cellpadding='5px'>
            <tr>
                <th style='width:5cm'><b>Name</b></th>
                <th><b>Gebutsdatum</b></th>
                <th><b>Telefon</b></th>
                <th><b>Handy</b></th>
                <th><b>Sonstiges</b></th>
            </tr>

            <?php foreach ($persons as $person): ?>
                <tr>
                    <td><?= $person->vorname() . " " . $person->nachname() ?></td>
                    <td><?= date('d.m.Y', strtotime($person->geb())) ?></td>
                    <td><?= $person->telefon() ?></td>
                    <td><?= $person->handy() ?></td>
                    <td><?= $person->sonstiges() ?></td>
                <tr>
            <?php endforeach; ?>

        </table>
    </body>
</html>
