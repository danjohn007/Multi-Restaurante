<?php
/**
 * SQL Syntax Test for SQLSTATE[42000] error detection
 * Tests complex queries that might cause MySQL syntax errors
 */

echo "=== SQL SYNTAX VALIDATION TEST ===\n\n";

// Load required files
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/Database.php';

try {
    // Test database connection
    $db = Database::getInstance();
    $connection = $db->getConnection();
    echo "✅ Database connection established\n\n";
    
    // Test 1: Complex Table availability query (most likely to cause SQLSTATE[42000])
    echo "🔍 Testing complex Table availability query...\n";
    
    $complexQuery = "
        SELECT t.* FROM tables t
        WHERE t.restaurant_id = ? 
        AND t.is_active = 1 
        AND t.capacity >= ?
        AND (t.valid_from IS NULL OR t.valid_from <= ?)
        AND (t.valid_until IS NULL OR t.valid_until >= ?)
        AND t.id NOT IN (
            SELECT DISTINCT CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(table_ids, ',', numbers.n), ',', -1) AS UNSIGNED) as table_id
            FROM reservations
            CROSS JOIN (
                SELECT 1 n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5
            ) numbers
            WHERE CHAR_LENGTH(table_ids) - CHAR_LENGTH(REPLACE(table_ids, ',', '')) >= numbers.n - 1
            AND reservation_date = ?
            AND ABS(TIME_TO_SEC(reservation_time) - TIME_TO_SEC(?)) < 7200
            AND status IN ('confirmed', 'seated')
        )
        ORDER BY t.capacity ASC, t.table_number ASC
    ";
    
    try {
        $stmt = $connection->prepare($complexQuery);
        echo "   ✅ Complex query prepared successfully\n";
        
        // Test with sample parameters
        $testParams = [1, 4, '2024-12-01', '2024-12-01', '2024-12-01', '19:00:00'];
        $stmt->execute($testParams);
        echo "   ✅ Complex query executed successfully\n";
        
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'SQLSTATE[42000]') !== false || strpos($e->getMessage(), '1064') !== false) {
            echo "   ❌ SQLSTATE[42000] syntax error detected: " . $e->getMessage() . "\n";
            echo "   🔧 This query needs to be fixed!\n";
        } else {
            echo "   ⚠️  Other database error: " . $e->getMessage() . "\n";
        }
    }
    
    // Test 2: FULLTEXT search in Restaurant model
    echo "\n🔍 Testing FULLTEXT search query...\n";
    
    $fulltextQuery = "
        SELECT *, MATCH(name, description, keywords) AGAINST(?) as relevance 
        FROM restaurants 
        WHERE is_active = 1 AND (
            name LIKE ? OR 
            description LIKE ? OR 
            keywords LIKE ? OR 
            food_type LIKE ? OR
            MATCH(name, description, keywords) AGAINST(?)
        )
        ORDER BY relevance DESC, name ASC
    ";
    
    try {
        $stmt = $connection->prepare($fulltextQuery);
        echo "   ✅ FULLTEXT query prepared successfully\n";
        
        $searchTerm = '%test%';
        $testParams = ['test', $searchTerm, $searchTerm, $searchTerm, $searchTerm, 'test'];
        $stmt->execute($testParams);
        echo "   ✅ FULLTEXT query executed successfully\n";
        
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'SQLSTATE[42000]') !== false || strpos($e->getMessage(), '1064') !== false) {
            echo "   ❌ SQLSTATE[42000] syntax error detected: " . $e->getMessage() . "\n";
            echo "   🔧 FULLTEXT index may be missing or query has syntax issues!\n";
        } else {
            echo "   ⚠️  Other database error: " . $e->getMessage() . "\n";
        }
    }
    
    // Test 3: FIND_IN_SET usage
    echo "\n🔍 Testing FIND_IN_SET queries...\n";
    
    $findInSetQuery = "
        SELECT 
            t.table_number,
            t.capacity,
            COUNT(r.id) as reservations_count
        FROM tables t
        LEFT JOIN reservations r ON FIND_IN_SET(t.id, r.table_ids) > 0
            AND r.reservation_date BETWEEN ? AND ?
            AND r.restaurant_id = ?
        WHERE t.restaurant_id = ? AND t.is_active = 1
        GROUP BY t.id, t.table_number, t.capacity
        ORDER BY t.table_number
    ";
    
    try {
        $stmt = $connection->prepare($findInSetQuery);
        echo "   ✅ FIND_IN_SET query prepared successfully\n";
        
        $testParams = ['2024-01-01', '2024-12-31', 1, 1];
        $stmt->execute($testParams);
        echo "   ✅ FIND_IN_SET query executed successfully\n";
        
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'SQLSTATE[42000]') !== false || strpos($e->getMessage(), '1064') !== false) {
            echo "   ❌ SQLSTATE[42000] syntax error detected: " . $e->getMessage() . "\n";
            echo "   🔧 FIND_IN_SET query has syntax issues!\n";
        } else {
            echo "   ⚠️  Other database error: " . $e->getMessage() . "\n";
        }
    }
    
    // Test 4: GROUP_CONCAT and HAVING clauses
    echo "\n🔍 Testing GROUP_CONCAT and HAVING queries...\n";
    
    $groupConcatQuery = "
        SELECT 
            c.*, 
            COUNT(DISTINCT r.id) as total_visits,
            COALESCE(SUM(b.total_amount), 0) as total_spent
        FROM customers c
        JOIN reservations r ON c.id = r.customer_id
        LEFT JOIN bills b ON r.id = b.reservation_id AND b.closed_at IS NOT NULL
        WHERE r.restaurant_id = ?
        GROUP BY c.id
        HAVING total_spent > 0
        ORDER BY total_spent DESC
        LIMIT 10
    ";
    
    try {
        $stmt = $connection->prepare($groupConcatQuery);
        echo "   ✅ GROUP/HAVING query prepared successfully\n";
        
        $testParams = [1];
        $stmt->execute($testParams);
        echo "   ✅ GROUP/HAVING query executed successfully\n";
        
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'SQLSTATE[42000]') !== false || strpos($e->getMessage(), '1064') !== false) {
            echo "   ❌ SQLSTATE[42000] syntax error detected: " . $e->getMessage() . "\n";
            echo "   🔧 GROUP/HAVING query has syntax issues!\n";
        } else {
            echo "   ⚠️  Other database error: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "🎯 SQL SYNTAX TEST SUMMARY\n";
    echo str_repeat("=", 60) . "\n";
    echo "All major complex queries tested for SQLSTATE[42000] syntax errors.\n";
    echo "If no syntax errors were reported above, the queries are MySQL-compatible.\n\n";
    
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "Cannot test SQL syntax without database connection.\n";
}
?>