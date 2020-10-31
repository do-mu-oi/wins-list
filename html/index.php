<html>
<head>
    <title>WINS Entries</title>
    <link rel="stylesheet" href="normalize.css">
    <style>
    body {
        margin: 1rem;
        background: #000;
        color: #0f0;
    }
    table {
        border-collapse: collapse;
        border: 1px solid #0f0;
        font-size: 13px;
    }
    thead tr {
        background: #0f0;
        color: #000;
    }
    tr:nth-child(2n) {
        background: #020;
    }
    th, td {
        padding: 0.2rem;
    }
    </style>
</head>
<body>
        <h1>WINS Entries</h1>
<?php
$filename = "/var/lib/samba/wins.dat";
if (is_file($filename)) {
    $records = array();
    $lines = file($filename);

    foreach ($lines as $line) {
        $record = explode(" ", $line);
        if (count($record) >= 3) {
            $host = str_replace("\"", "", $record[0]);
            $ttl = $record[1];
            $address = array_slice($record, 2, count($record) - 3);
            $flags = end($record);

            if (preg_match("/[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+/", $address[0])) {
                $records[] = array(
                    "host" => $host,
                    "ttl" => $ttl,
                    "address" => $address,
                    "flags" => $flags,
                );
            }
        }
    }

    $records_host = array();
    foreach ($records as $record) {
        $records_host[] = $record["host"];
    }

    array_multisort($records, SORT_DESC, SORT_STRING, $records_host);
?>
    <table>
        <thead>
            <tr>
                <th>NAME#TYPE</th>
                <th>ADDRESS</th>
                <th>TTL</th>
                <th>FLAGS</th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach ($records as $record) {
?>
            <tr>
                <td><?= $record["host"] ?></td>
                <td><?= join(", ", $record["address"]) ?></td>
                <td><?= $record["ttl"] ?></td>
                <td><?= $record["flags"] ?></td>
            </tr>
<?php
    }
?>
        </tbody>
    <table>
<?php
} else {
?>
    <p><?= $filename ?> not found.</p>
<?php
}
?>
</body>
</html>