#!/bin/bash

# Function to check if a file exists
check_file_exists() {
    if [ ! -f "$1" ]; then
        if [[ "$1" == *"index.php" ]]; then
            echo "❌ Missing required file: $1 (see documentation: https://devdocs.prestashop-project.org/8/modules/creation/tutorial/#keeping-things-secure)" >> errors.log
        elif [[ "$1" == *".htaccess" ]]; then
            echo "❌ Missing required file: $1 (see documentation: https://docs.cloud.prestashop.com/10-validation-checklist/#an-htaccess-file-exists-in-the-root-folder-of-the-module)" >> errors.log
        else
            echo "❌ Missing required file: $1" >> errors.log
        fi
    fi
}

# Function to check PHP file header
check_php_header() {
    if ! grep -q "!defined('_PS_VERSION_')" "$1"; then
        echo "❌ Missing PrestaShop version check in: $1 (see documentation: https://docs.cloud.prestashop.com/10-validation-checklist/#php-files-are-executed-in-prestashop-context)" >> errors.log
    fi
}

# Clear errors log
> errors.log

echo "🔍 Checking module requirements..."

# 1. Check for index.php in all directories (except vendor, node_modules, .git, .github)
for dir in $(find . -type d ! -path "*/vendor/*" ! -path "*/node_modules/*" ! -path "*/.git/*" ! -path "*/.github/*"); do
    check_file_exists "${dir}/index.php"
done

# 2. Check .htaccess in root directory
check_file_exists ".htaccess"

# 3. Check for logo.png
check_file_exists "logo.png"

# 4. Check PHP files for version check (excluding translations directory, cs-fixer files, test files, vendor, and git directories)
for php_file in $(find . -name "*.php" ! -name "index.php" ! -path "*/translations/*" ! -name "*cs-fixer*" ! -path "*/tests/*" ! -path "*/test/*" ! -path "*/vendor/*" ! -path "*/.git/*"); do
    check_php_header "$php_file"
done

# 5. Check .htaccess in log directories
if [ -d "log" ] || [ -d "logs" ]; then
    for log_dir in "log" "logs"; do
        if [ -d "$log_dir" ]; then
            check_file_exists "${log_dir}/.htaccess"
        fi
    done
fi

# Display all errors if any exist
if [ -s errors.log ]; then
    echo "❌ Found the following errors:"
    cat errors.log
    exit 1
else
    echo "✅ Module passed all checks successfully!"
fi
