# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a PHP-based enterprise resource planning (ERP) system for an elevator interior manufacturing company (미래기업). The system manages manufacturing processes for elevator ceilings, jambs, and other interior components.

## Technology Stack

- **Backend**: PHP 7+ (mix of PDO and mysqli)
- **Frontend**: HTML, CSS, JavaScript (jQuery)
- **Database**: MySQL/MariaDB
- **External Libraries**:
  - Composer packages: dompdf/dompdf, google/apiclient
  - PHPMailer for email functionality
  - PHPExcel for spreadsheet operations
  - Bootstrap for UI components
  - Chart.js and Highcharts for data visualization

## Key Directories

- `/ceiling/` - Elevator ceiling manufacturing management
- `/jamb1.php`, `/jamb_left.php` - Jamb (door frame) manufacturing
- `/QC/` - Quality control and inspection modules
- `/steel/` - Steel material inventory and processing
- `/analysis/` - Data analysis and reporting
- `/eworks/` - Electronic workflow and approval system
- `/member/` - User management
- `/lib/` - Core database connection and utilities
- `/vendor/` - Composer dependencies

## Database Configuration

Database connections are configured in `/lib/mydb.php` and `/lib/mydbsqli.php`:
- Default database: `mirae8440`
- Charset: UTF8MB4
- Connection method: PDO (preferred) and mysqli (legacy)

## Development Commands

```bash
# Install PHP dependencies
composer install

# No build process required - PHP application runs directly
```

## Architecture Notes

### Database Connection Pattern
The application uses a dual database connection approach:
- **PDO** (`/lib/mydb.php`): Modern prepared statements for new code
- **mysqli** (`/lib/mydbsqli.php`): Legacy connection for older modules

### Session Management
- Sessions handled via `/session.php` and `/session_header.php`
- User authentication state stored in `$_SESSION["name"]`
- Database selection stored in `$_SESSION['DB']`

### Module Structure
Each major feature (ceiling, steel, QC, etc.) follows a similar pattern:
- `list.php` - Display records in a table
- `view.php` - View single record details
- `write_form.php` - Create/edit form
- `insert.php` - Process form submission
- `delete.php` - Delete records
- `process_DB.php` or `rowDB.php` - AJAX data processing

### Common Functions
Utility functions in `/common.php`:
- `NullCheckDate()` - Date validation
- `isNotNull()` - Null checking for dates
- `is_string_valid()` - String validation
- `trans_date()` - Date formatting

## Security Considerations

- Database credentials are hardcoded in connection files (needs environment variables)
- Mixed use of prepared statements and direct queries (migration to PDO recommended)
- Session-based authentication without visible CSRF protection