<?php
/**
 * Debug reservation form submission
 * Creates a minimal test to identify the exact issue
 */

// Check if we can create a simple test environment
echo "=== Reservation Form Debug Test ===\n\n";

// Simulate form submission with debugging
echo "1. Testing potential issues:\n";

// Check if the issue is in the selectedTable.id property
echo "   • Checking selectedTable property access...\n";

// Read the reserve.php file to extract the exact JavaScript code
$reserveContent = file_get_contents(__DIR__ . '/../app/views/reservation/reserve.php');

// Extract the JavaScript section
$jsStart = strpos($reserveContent, '<script>');
$jsEnd = strrpos($reserveContent, '</script>');
$jsCode = substr($reserveContent, $jsStart + 8, $jsEnd - $jsStart - 8);

// Check for potential issues in the confirmation function
echo "   • Analyzing confirmation button event listener...\n";

if (strpos($jsCode, "formData.append('selected_table_id', selectedTable.id);") !== false) {
    echo "     ✓ Found: selectedTable.id is used for form submission\n";
} else {
    echo "     ✗ selectedTable.id usage not found\n";
}

// Check if there are any console.log statements for debugging
if (strpos($jsCode, 'console.log') !== false) {
    echo "     ℹ Debug console.log statements found\n";
} else {
    echo "     ℹ No debug console.log statements found\n";
}

// Check for error handling in the submission
if (strpos($jsCode, 'catch(error)') !== false) {
    echo "     ✓ Error handling found in JavaScript\n";
} else {
    echo "     ⚠ No error handling found in JavaScript\n";
}

echo "\n2. Analyzing potential root causes:\n";

// Check if the form submission is wrapped in any conditions
$confirmationStart = strpos($jsCode, "document.getElementById('confirmReservationBtn').addEventListener('click'");
$confirmationEnd = strpos($jsCode, '});', $confirmationStart);
$confirmationCode = substr($jsCode, $confirmationStart, $confirmationEnd - $confirmationStart);

echo "   • Confirmation button click handler analysis:\n";

if (strpos($confirmationCode, 'selectedTable.id') !== false) {
    echo "     ✓ selectedTable.id is accessed in click handler\n";
} else {
    echo "     ✗ selectedTable.id is NOT accessed in click handler\n";
}

if (strpos($confirmationCode, 'App.submitFormAjaxWithData') !== false) {
    echo "     ✓ App.submitFormAjaxWithData is called\n";
} else {
    echo "     ✗ App.submitFormAjaxWithData is NOT called\n";
}

// Check for any validation before submission
if (strpos($confirmationCode, 'if') !== false) {
    echo "     ℹ Conditional logic found in click handler\n";
} else {
    echo "     ℹ No conditional logic found in click handler\n";
}

echo "\n3. Specific Issue Identification:\n";

// The most likely issue: Check if selectedTable might be null
echo "   • Checking for null selectedTable validation...\n";

if (strpos($confirmationCode, 'selectedTable') !== false) {
    echo "     ✓ selectedTable is referenced\n";
    
    // Check if there's validation for selectedTable being null
    if (strpos($confirmationCode, '!selectedTable') !== false || 
        strpos($confirmationCode, 'selectedTable === null') !== false ||
        strpos($confirmationCode, 'selectedTable == null') !== false) {
        echo "     ✓ selectedTable null check found\n";
    } else {
        echo "     ⚠ NO selectedTable null check found - POTENTIAL ISSUE!\n";
    }
} else {
    echo "     ✗ selectedTable is NOT referenced\n";
}

echo "\n4. Recommendation:\n";
echo "   The most likely issue is that selectedTable.id is being accessed\n";
echo "   without checking if selectedTable is null or if it has an 'id' property.\n";
echo "   This would cause a JavaScript error and prevent form submission.\n";

echo "\n5. Quick Fix Needed:\n";
echo "   Add validation in the confirmation button click handler:\n";
echo "   - Check if selectedTable exists\n";
echo "   - Check if selectedTable.id exists\n";
echo "   - Add error handling for the form submission\n";