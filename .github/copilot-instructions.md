# YForm Field - REDAXO Addon Extension

YForm Field is a REDAXO 5 addon that extends YForm 4 with additional field types, validations, and actions. This is a PHP addon that provides 26 custom field types, form validations, and actions for the REDAXO CMS YForm component.

Always reference these instructions first and fallback to search or bash commands only when you encounter unexpected information that does not match the info here.

## Working Effectively

### Bootstrap and Install Dependencies
- Install PHP dependencies: `composer install --prefer-dist --no-progress --no-interaction`
  - Takes 17-50 seconds depending on network speed. NEVER CANCEL. Set timeout to 120+ seconds.
  - May require authentication against GitHub; if fails, try: `COMPOSER_DISABLE_NETWORK=1 composer install`
  - Installs PHP-CS-Fixer and related tools to `vendor/` directory (37 packages)

### Code Quality and Validation
- Check PHP syntax of all files: `find . -name "*.php" -exec php -l {} \;`
  - Takes approximately 98 seconds for all 2520 PHP files (including vendor). NEVER CANCEL. Set timeout to 180+ seconds.
  - For project files only (56 files): `find . -maxdepth 3 -name "*.php" -not -path "./vendor/*" -exec php -l {} \;`
  - Should report "No syntax errors detected" for all files
- Run code style dry-run: `composer cs-dry`
  - Takes approximately 1 second. Uses PHP-CS-Fixer with REDAXO config on 55 project files.
  - Should report "Found 0 of 55 files that can be fixed"
- Fix code style issues: `composer cs-fix`
  - Takes approximately 1 second. Automatically fixes code style violations.
  - Should report "Fixed 0 of 55 files" if code is already compliant

### SCSS/CSS Compilation
- CSS is compiled from SCSS during addon installation via `install.php`
- Source SCSS: `scss/be.scss` 
- Compiled CSS: `assets/be.min.css`
- Requires REDAXO environment with `be_style` addon for compilation
- Manual compilation not available outside REDAXO installation

## Validation

### Always Validate Changes
- ALWAYS run `composer cs-dry` before committing to ensure code style compliance
- ALWAYS run PHP syntax check: `find . -name "*.php" -exec php -l {} \;` 
- Check that no new syntax errors are introduced in your changes
- The CI workflow (.github/workflows/code-style.yml) will fail if code style violations exist

### Testing and Manual Validation
- This addon has NO unit tests or automated test suite
- Manual validation requires a full REDAXO 5 installation with YForm 4
- Cannot be functionally tested outside a REDAXO environment
- Test field types by creating YForm forms in REDAXO backend and testing:
  - HTML5 datetime input fields (`datetime_local`)
  - Domain selection fields (`domain`)
  - OpenAI integration fields (`openai_prompt`, `openai_spellcheck`)
  - Custom link widgets (`custom_link`)
  - Privacy policy checkboxes (`privacy_policy`)
  - Tabbed form interfaces (`tabs`)

### Common Tasks and File Locations

#### Key Directories
- `lib/yform/value/` - Field type implementations (26 custom field types)
- `lib/yform/validate/` - Form validation implementations  
- `lib/yform/action/` - Form action implementations
- `assets/` - Compiled CSS, JavaScript, and SVG assets
- `scss/` - SCSS source files for styling
- `ytemplates/` - YForm template files for field rendering
- `pages/` - REDAXO backend interface pages
- `fragments/` - HTML fragment templates

#### Main Files
- `boot.php` - Addon bootstrap and initialization
- `package.yml` - Addon metadata and requirements
- `composer.json` - PHP dependencies (PHP-CS-Fixer)
- `.php-cs-fixer.dist.php` - Code style configuration

#### CI and Publishing
- `.github/workflows/code-style.yml` - Automated code style checking
- `.github/workflows/publish-to-redaxo-org.yml` - Automated publishing on release

## Development Workflows

### Adding New Field Types
- Create new PHP class in `lib/yform/value/` extending `rex_yform_value_abstract`
- Implement required methods: `enterObject()`, `preValidateAction()` 
- Add corresponding template files in `ytemplates/bootstrap/`
- Update README.md documentation table
- Test manually in REDAXO installation

### Code Style Maintenance
- Run `composer cs-fix` to automatically fix style violations
- Code follows REDAXO 5 coding standards via `redaxo/php-cs-fixer-config`
- GitHub Actions automatically runs style checks on push/PR
- Style violations will cause CI to fail

### Publishing and Releases
- Releases are automatically published to REDAXO.org via GitHub Actions
- Version defined in `package.yml`
- Release notes from GitHub release body are used

## System Requirements
- PHP 8.3+ (intl extension required)
- REDAXO 5.18.3+
- YForm 4.1.1+
- Media Manager 2.16.0+

## Important Notes
- This addon is a REDAXO-specific extension and cannot run standalone
- No traditional build process - PHP files are executed directly
- SCSS compilation happens during REDAXO addon installation
- No automated testing - requires manual validation in REDAXO
- Focus on field types for forms, not general web application features