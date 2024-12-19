<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $dbusername = "root"; // Your database username
    $dbpassword = ""; // Your database password
    $dbname = "project"; // Your database name

    try {
        // Create connection
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Get club ID from the request
        if (isset($_GET['id'])) {
            $clubId = intval($_GET['id']);

            // Prepare and execute delete query
            $stmt = $conn->prepare("DELETE FROM newdatakoko WHERE id = ?");
            $stmt->bind_param("i", $clubId);

            if ($stmt->execute()) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to delete club."]);
            }

            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request. Club ID missing."]);
        }

        $conn->close();
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}
?>
