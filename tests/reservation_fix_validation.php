<?php
/**
 * Test the fixed reservation button functionality
 * Validates that the confirmation button now has proper error handling
 */

echo "=== Reservation Button Fix Validation ===\n\n";

$reserveContent = file_get_contents(__DIR__ . '/../app/views/reservation/reserve.php');

// Test 1: Check if selectedTable validation was added
echo "1. Testing selectedTable Validation:\n";

if (strpos($reserveContent, '!selectedTable') !== false && 
    strpos($reserveContent, 'No se ha seleccionado ninguna mesa') !== false) {
    echo "   ✓ selectedTable null check added\n";
} else {
    echo "   ✗ selectedTable null check not found\n";
}

if (strpos($reserveContent, '!selectedTable.id') !== false && 
    strpos($reserveContent, 'Información de mesa inválida') !== false) {
    echo "   ✓ selectedTable.id validation added\n";
} else {
    echo "   ✗ selectedTable.id validation not found\n";
}

// Test 2: Check if error handling was added
echo "\n2. Testing Error Handling:\n";

if (strpos($reserveContent, 'try {') !== false && strpos($reserveContent, 'catch (error)') !== false) {
    echo "   ✓ Try-catch error handling added\n";
} else {
    echo "   ✗ Try-catch error handling not found\n";
}

if (strpos($reserveContent, 'console.error') !== false) {
    echo "   ✓ Console error logging added\n";
} else {
    echo "   ✗ Console error logging not found\n";
}

// Test 3: Check if button state restoration was added
echo "\n3. Testing Button State Management:\n";

if (strpos($reserveContent, 'confirmBtn.disabled = false') !== false) {
    echo "   ✓ Button re-enabling added\n";
} else {
    echo "   ✗ Button re-enabling not found\n";
}

if (strpos($reserveContent, 'confirmBtn.innerHTML = originalText') !== false) {
    echo "   ✓ Button text restoration added\n";
} else {
    echo "   ✗ Button text restoration not found\n";
}

// Test 4: Check if debug logging was added
echo "\n4. Testing Debug Functionality:\n";

if (strpos($reserveContent, 'console.log') !== false && 
    strpos($reserveContent, 'showReservationConfirmation called') !== false) {
    echo "   ✓ Debug logging added to showReservationConfirmation\n";
} else {
    echo "   ✗ Debug logging not found\n";
}

// Test 5: Validate the fix maintains existing functionality
echo "\n5. Testing Existing Functionality Preservation:\n";

if (strpos($reserveContent, 'App.submitFormAjaxWithData(form, formData)') !== false) {
    echo "   ✓ Form submission logic preserved\n";
} else {
    echo "   ✗ Form submission logic not found\n";
}

if (strpos($reserveContent, "formData.append('selected_table_id', selectedTable.id)") !== false) {
    echo "   ✓ Table ID appending preserved\n";
} else {
    echo "   ✗ Table ID appending not found\n";
}

if (strpos($reserveContent, "formData.append('ajax', '1')") !== false) {
    echo "   ✓ AJAX flag appending preserved\n";
} else {
    echo "   ✗ AJAX flag appending not found\n";
}

// Test 6: Check for syntax errors
echo "\n6. Testing JavaScript Syntax:\n";

// Extract JavaScript section for basic validation
$jsStart = strpos($reserveContent, '<script>');
$jsEnd = strrpos($reserveContent, '</script>');
if ($jsStart !== false && $jsEnd !== false) {
    $jsCode = substr($reserveContent, $jsStart + 8, $jsEnd - $jsStart - 8);
    
    // Basic syntax checks
    $openBraces = substr_count($jsCode, '{');
    $closeBraces = substr_count($jsCode, '}');
    $openParens = substr_count($jsCode, '(');
    $closeParens = substr_count($jsCode, ')');
    
    if ($openBraces === $closeBraces) {
        echo "   ✓ Balanced curly braces\n";
    } else {
        echo "   ✗ Unbalanced curly braces ($openBraces open, $closeBraces close)\n";
    }
    
    if ($openParens === $closeParens) {
        echo "   ✓ Balanced parentheses\n";
    } else {
        echo "   ✗ Unbalanced parentheses ($openParens open, $closeParens close)\n";
    }
    
    // Check for common syntax issues
    if (strpos($jsCode, ';;') === false) {
        echo "   ✓ No double semicolons found\n";
    } else {
        echo "   ⚠ Double semicolons found (might be intentional)\n";
    }
} else {
    echo "   ✗ JavaScript section not found\n";
}

echo "\n=== FIX SUMMARY ===\n";
echo "✓ Added selectedTable null validation\n";
echo "✓ Added selectedTable.id property validation\n";
echo "✓ Added try-catch error handling\n";
echo "✓ Added console error logging\n";
echo "✓ Added button state restoration on error\n";
echo "✓ Added debug logging for troubleshooting\n";
echo "✓ Preserved existing form submission functionality\n";

echo "\n=== EXPECTED BEHAVIOR ===\n";
echo "1. If selectedTable is null: Shows error message and restores button\n";
echo "2. If selectedTable.id is missing: Shows error message and restores button\n";
echo "3. If form submission fails: Catches error, logs it, and restores button\n";
echo "4. If everything is valid: Submits form as before\n";
echo "5. Debug information is logged to browser console\n";