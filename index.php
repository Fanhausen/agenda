<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar with Events</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php
// Fungsi untuk menampilkan kalender
function showCalendar($year, $month, $events) {
    echo '<table>';
    echo '<tr>';
    echo '<th colspan="7">';
    echo '<form method="get" action="">';
    echo '    <select name="month">';
    for ($i = 1; $i <= 12; $i++) {
        $selected = ($i == $month) ? 'selected' : '';
        echo "        <option value=\"$i\" $selected>" . date("F", mktime(0, 0, 0, $i, 1, $year)) . "</option>";
    }
    echo '    </select>';
    echo '    <input type="number" name="year" value="' . $year . '" min="1900" max="2100" required>';
    echo '    <input type="submit" value="Go">';
    echo '</form>';
    echo '</th>';
    echo '</tr>';
    echo '<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>';

    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $lastDayOfMonth = mktime(0, 0, 0, $month + 1, 0, $year);
    $currentDay = 1;

    while ($currentDay <= date("t", $lastDayOfMonth)) {
        echo '<tr>';
        for ($i = 0; $i < 7; $i++) {
            $currentDate = date("Y-m-d", mktime(0, 0, 0, $month, $currentDay, $year));

            if (($currentDay <= date("t", $lastDayOfMonth) && $i >= date("w", $firstDayOfMonth)) || $currentDay > 1) {
                echo '<td>';
                echo $currentDay;
                // Tampilkan event jika ada
                if (isset($events[$currentDate])) {
                    echo '<br>';
                    foreach ($events[$currentDate] as $event) {
                        echo '<span style="color: green;">' . $event . '</span><br>';
                    }
                }
                echo '</td>';
                $currentDay++;
            } else {
                echo '<td></td>';
            }
        }
        echo '</tr>';
    }

    echo '</table>';
}

// Fungsi untuk menetapkan event
function setEvent($events, $date, $eventText) {
    if (!isset($events[$date])) {
        $events[$date] = [];
    }
    $events[$date][] = $eventText;
    return $events;
}


// Tampilkan kalender untuk bulan dan tahun yang dipilih
if (isset($_GET['month']) && isset($_GET['year'])) {
    $selectedMonth = $_GET['month'];
    $selectedYear = $_GET['year'];
} else {
    $selectedMonth = date("n");
    $selectedYear = date("Y");
}

// Inisialisasi events (di sini menggunakan array asosiatif untuk menyimpan events)
$events = [];

// Proses form jika disubmit untuk menetapkan event
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventDate = $_POST['date'];
    $eventText = $_POST['text'];

    // Validasi tanggal
    if (DateTime::createFromFormat('Y-m-d', $eventDate) !== false) {
        // Set event
        $events = setEvent($events, $eventDate, $eventText);
    } else {
        echo '<p style="color: red;">Invalid date format. Please use YYYY-MM-DD.</p>';
    }
}

// Menampilkan kalender dengan events
showCalendar($selectedYear, $selectedMonth, $events);
?>

<!-- Form untuk menetapkan event -->
<h2>Set Event</h2>
<form method="post" action="">
    <label for="date">Date (YYYY-MM-DD): </label>
    <input type="text" name="date" required>
    <br>
    <label for="text">Event Text: </label>
    <textarea name="text" required></textarea>
    <br>
    <input type="submit" name="submit" value="Set Event">
</form>

</body>
</html>
