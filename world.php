<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the 'country' and 'lookup' parameters from the URL
    $country = $_GET['country'] ?? '';
    $lookup = $_GET['lookup'] ?? '';
    $country = htmlspecialchars($country); // Sanitize the country input

    if ($lookup === 'cities') {
        // If looking for cities, perform an SQL JOIN to get city details
        $stmt = $conn->prepare("
            SELECT cities.name AS city_name, cities.district, cities.population
            FROM cities
            JOIN countries ON cities.country_code = countries.code
            WHERE countries.name LIKE :country
        ");
        $stmt->execute(['country' => "%$country%"]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Default to country information if 'lookup' is not set to 'cities'
        if (!empty($country)) {
            $stmt = $conn->prepare("SELECT * FROM countries WHERE name LIKE :country");
            $stmt->execute(['country' => "%$country%"]);
        } else {
            $stmt = $conn->query("SELECT * FROM countries");
        }
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!-- If lookup is 'cities', display the cities table -->
<?php if ($lookup === 'cities'): ?>
    <table border="1" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>City Name</th>
                <th>District</th>
                <th>Population</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['city_name']) ?></td>
                    <td><?= htmlspecialchars($row['district']) ?></td>
                    <td><?= htmlspecialchars($row['population']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <!-- If it's not a city lookup, display country information -->
    <table border="1" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Country Name</th>
                <th>Continent</th>
                <th>Independence Year</th>
                <th>Head of State</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['continent']) ?></td>
                    <td><?= htmlspecialchars($row['independence_year']) ?></td>
                    <td><?= htmlspecialchars($row['head_of_state']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
