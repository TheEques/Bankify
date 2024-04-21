<?php
session_start();

// Check if prediction is available
if (isset($_SESSION['prediction'])) {
    $prediction = $_SESSION['prediction'];

    // If loan is not approved
    if ($prediction == 'No') {
        // Fetch relevant information from session
        $credit_history = $_SESSION['credit_history'];
        $income = $_SESSION['income'];
        $employment_status = $_SESSION['employment_status'];

        // Determine rejection reason based on multiple factors
        $rejection_reason = ""; // Initialize rejection reason
        
        // Example: If credit history is poor or missing
        if ($credit_history < 0.5 || $credit_history === null) {
            $rejection_reason .= "Your credit history is poor or missing. ";
        }
        
        // Example: If income is insufficient
        if ($income < 30000) {
            $rejection_reason .= "Insufficient income. ";
        }

        // Example: If employment status is unemployed
        if ($employment_status === 'Unemployed') {
            $rejection_reason .= "You are unemployed. ";
        }

        // Add more conditions based on your specific criteria

        // Display rejection reason
        echo "<p id='res'>The loan is not approved. Reason: " . $rejection_reason . "</p>";
    } else {
        // If loan is approved
        echo "<p id='res'>Congratulations! The loan is approved.</p>";
    }
} else {
    // If prediction is not available, display a default message
    echo "<p id='res'>Loan approval prediction not available.</p>";
}
?>
