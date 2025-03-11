<?php
    // If evidence is not a computer device redirects
    $query = "SELECT EvidenceType FROM evidence WHERE Identifier = ? AND EvidenceID = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ss", $identifier, $evidenceID);  
    $stmt->execute();
    $results = $stmt->get_result();
    $row = $results->fetch_assoc();

    if ($row['EvidenceType'] === "Computer") {
        echo '<a href="viewLBU04.php?identifier=' . htmlspecialchars($identifier) . '&EvidenceID=' . htmlspecialchars($evidenceID) . '" id="navcase-button">LBU04</a>';
        $stmt->close();
    }
?>
