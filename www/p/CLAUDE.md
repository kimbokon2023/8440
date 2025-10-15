# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a PHP-based enterprise resource planning (ERP) system for an elevator interior manufacturing company (미래기업). The system manages manufacturing processes for elevator ceilings, jambs, and other interior components. This specific module (`/p/`) handles the production workflow management and quality control for manufacturing processes.

## Technology Stack

- **Backend**: PHP 7.3+ with PDO for database operations
- **Frontend**: HTML, CSS, JavaScript (jQuery)
- **Database**: MySQL/MariaDB (database: `mirae8440`)
- **External Libraries**:
  - Composer packages: dompdf/dompdf, google/apiclient, monolog/monolog
  - Bootstrap for UI components
  - Signature pad functionality for approval workflows

## Development Commands

```bash
# Install PHP dependencies
composer install

# No build process required - PHP application runs directly
# Access via web server: http://localhost/mirae8440/www/p/
```

## Key Architecture

### Environment Management
The application uses a sophisticated environment detection system:
- **Local Environment**: Detected via HTTP_HOST patterns (8440.local, localhost, 127.0.0.1, private IPs)
- **Server Environment**: Production hosting environment
- Configuration automatically switches between local and server settings

### Database Architecture
- **Connection**: Centralized through `/lib/mydb.php` using PDO with prepared statements
- **Environment-aware**: Database credentials automatically selected based on environment
- **Session-based DB selection**: `$_SESSION['DB']` can override default database
- **Transaction support**: Uses PDO transactions for data integrity

### File Structure Pattern
Each module follows a consistent pattern:
- `index.php` - Main listing/dashboard view
- `view.php` - Individual record detail view
- `process_DB.php` - Database operations and AJAX handlers
- `process_done.php` - Completion workflow handlers
- `customer_*.php` - Customer-facing interfaces
- `reg_*.php` - Registration/data entry forms
- `*_insert.php` - Form processing and database insertion

### Session Management
- Bootstrap file (`../bootstrap.php`) initializes sessions and database connections
- User authentication via `$_SESSION["name"]` and `$_SESSION["level"]`
- Mobile detection built into bootstrap for responsive behavior
- Role-based access control with level-based permissions

### Common Patterns

#### Database Operations
```php
// Standard pattern for database queries
require_once "../lib/mydb.php";
$pdo = db_connect();

try {
    $pdo->beginTransaction();
    $sql = "UPDATE table SET field=? WHERE id=? LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $value, PDO::PARAM_STR);
    $stmt->execute();
    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log($e->getMessage());
}
```

#### Update Logging
Most modules implement update logging:
```php
$session_name = $_SESSION["name"] ?? '';
$data = date("Y-m-d H:i:s") . " - " . $session_name . " ";
$update_log = $data . $existing_log . "&#10"; // Textarea newlines
```

#### Window Management
JavaScript pattern for popup window handling:
```javascript
// Refresh parent window after operations
if (window.opener && !window.opener.closed) {
    window.opener.location.reload();
}
window.location.href = 'target.php?params';
```

## Security Considerations

- Uses prepared statements with PDO for SQL injection prevention
- Session-based authentication with level checking
- Transaction rollback on database errors
- Input validation through `$_REQUEST` parameter checking with null coalescing
- Error logging instead of displaying sensitive error information

## Module Integration

This `/p/` module integrates with the broader ERP system:
- Shares common database and session management with parent modules
- Uses global bootstrap for environment-aware initialization
- Consistent navigation and permission patterns across modules
- Signature pad integration for approval workflows
- File upload handling for documentation and images

## Development Notes

- The system supports both local development and production deployment through environment detection
- Database operations use consistent PDO patterns with error handling
- Mobile responsiveness is handled at the bootstrap level
- Update logging provides audit trails for manufacturing processes
- Image and signature management for quality control documentation