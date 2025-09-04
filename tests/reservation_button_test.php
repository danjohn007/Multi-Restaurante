<?php
/**
 * Test for reservation confirmation button functionality
 * Validates the complete reservation flow from availability check to confirmation
 */

echo "=== Reservation Button Functionality Test ===\n\n";

// Test 1: Check reservation form structure
echo "1. Testing Reservation Form Structure:\n";
$reserveContent = file_get_contents(__DIR__ . '/../app/views/reservation/reserve.php');

if (strpos($reserveContent, 'showReservationConfirmation(this)') !== false) {
    echo "   ✓ Form calls showReservationConfirmation with 'this' reference\n";
} else {
    echo "   ✗ Form confirmation call not found\n";
}

if (strpos($reserveContent, 'App.submitFormAjaxWithData(form, formData)') !== false) {
    echo "   ✓ Form submission uses App.submitFormAjaxWithData method\n";
} else {
    echo "   ✗ Form submission method not found\n";
}

if (strpos($reserveContent, 'confirmReservationBtn') !== false) {
    echo "   ✓ Confirmation modal button exists\n";
} else {
    echo "   ✗ Confirmation modal button not found\n";
}

// Test 2: Check JavaScript app.js functionality
echo "\n2. Testing App.js Support:\n";
$appJsContent = file_get_contents(__DIR__ . '/../public/js/app.js');

if (strpos($appJsContent, 'submitFormAjaxWithData: function(form, formData)') !== false) {
    echo "   ✓ submitFormAjaxWithData method exists in app.js\n";
} else {
    echo "   ✗ submitFormAjaxWithData method not found in app.js\n";
}

if (strpos($appJsContent, 'fetch(action, {') !== false) {
    echo "   ✓ AJAX fetch implementation exists\n";
} else {
    echo "   ✗ AJAX fetch implementation not found\n";
}

// Test 3: Check ReservationController processing
echo "\n3. Testing ReservationController:\n";
$controllerContent = file_get_contents(__DIR__ . '/../app/controllers/ReservationController.php');

if (strpos($controllerContent, 'public function processReservation($id)') !== false) {
    echo "   ✓ processReservation method exists\n";
} else {
    echo "   ✗ processReservation method not found\n";
}

if (strpos($controllerContent, 'selected_table_id') !== false) {
    echo "   ✓ Controller handles selected_table_id parameter\n";
} else {
    echo "   ✗ Controller doesn't handle selected_table_id parameter\n";
}

if (strpos($controllerContent, 'reservation/confirmation/') !== false) {
    echo "   ✓ Controller redirects to confirmation page\n";
} else {
    echo "   ✗ Controller doesn't redirect to confirmation page\n";
}

// Test 4: Check route configuration
echo "\n4. Testing Route Configuration:\n";
$routeContent = file_get_contents(__DIR__ . '/../public/index.php');

if (strpos($routeContent, "post('restaurant/(\\d+)/reserve', 'ReservationController@processReservation')") !== false) {
    echo "   ✓ POST route for reservation processing exists\n";
} else {
    echo "   ✗ POST route for reservation processing not found\n";
}

if (strpos($routeContent, "get('reservation/confirmation/(\\d+)', 'ReservationController@confirmation')") !== false) {
    echo "   ✓ GET route for confirmation page exists\n";
} else {
    echo "   ✗ GET route for confirmation page not found\n";
}

// Test 5: Analyze potential issues in the JavaScript code
echo "\n5. Analyzing Potential JavaScript Issues:\n";

// Check for form reference issue in showReservationConfirmation
if (preg_match('/function showReservationConfirmation\(([^)]+)\)/', $reserveContent, $matches)) {
    echo "   ✓ showReservationConfirmation function parameter: " . $matches[1] . "\n";
    
    // Check if the form parameter is used correctly inside the function
    $functionStart = strpos($reserveContent, 'function showReservationConfirmation');
    $functionEnd = strpos($reserveContent, '}', strpos($reserveContent, 'App.submitFormAjaxWithData', $functionStart));
    $functionBody = substr($reserveContent, $functionStart, $functionEnd - $functionStart);
    
    if (strpos($functionBody, 'const formData = new FormData(form)') !== false) {
        echo "   ✓ Function correctly uses form parameter for FormData\n";
    } else {
        echo "   ✗ Function doesn't use form parameter correctly\n";
    }
    
    if (strpos($functionBody, 'App.submitFormAjaxWithData(form, formData)') !== false) {
        echo "   ✓ Function passes form to submitFormAjaxWithData\n";
    } else {
        echo "   ✗ Function doesn't pass form to submitFormAjaxWithData\n";
    }
} else {
    echo "   ✗ showReservationConfirmation function not found\n";
}

// Test 6: Check for common JavaScript errors
echo "\n6. Checking for Common JavaScript Issues:\n";

// Check for syntax errors in the JavaScript section
$jsStart = strpos($reserveContent, '<script>');
$jsEnd = strrpos($reserveContent, '</script>');
if ($jsStart !== false && $jsEnd !== false) {
    $jsCode = substr($reserveContent, $jsStart + 8, $jsEnd - $jsStart - 8);
    
    // Check for common issues
    if (strpos($jsCode, 'selectedTable') !== false) {
        echo "   ✓ selectedTable variable is referenced\n";
    } else {
        echo "   ✗ selectedTable variable not found\n";
    }
    
    if (strpos($jsCode, 'addEventListener') !== false) {
        echo "   ✓ Event listeners are properly attached\n";
    } else {
        echo "   ✗ Event listeners not found\n";
    }
}

echo "\n=== TEST SUMMARY ===\n";
echo "These tests check the basic structure and flow of the reservation system.\n";
echo "If any tests fail, they indicate potential issues with the 'confirmar reservación' button.\n";
echo "The most likely issue is in the JavaScript form reference handling.\n";