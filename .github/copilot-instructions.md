# YForm Field - REDAXO Addon Extension

YForm Field is a REDAXO 5 addon that extends YForm 4 with additional field types, validations, and actions. This is a PHP addon that provides 26 custom field types, form validations, and actions for the REDAXO CMS YForm component.

This addon is an extension for the REDAXO CMS (https://github.com/redaxo/redaxo) that provides enhanced form functionality. It is based on the YForm extension and YOrm (a database table management system with user interface and ORM for data processing), and also uses core classes (REDAXO Core System) and established extensions for REDAXO.

You can find information about REDAXO and corresponding classes and concepts at:
- Technical documentation and tutorials for REDAXO (e.g., socket connections, extension points, backend pages, and service classes like `rex_sql`, `rex_config_form`)
- The core system and core addons of REDAXO: https://github.com/redaxo/redaxo, especially https://github.com/redaxo/redaxo/tree/main/src/core/lib/
- Note: Class files do not contain the `rex_` prefix in their names, e.g., the `rex_socket` class is in `redaxo/src/core/lib/util/socket/socket.php`, or the `rex_fragment` class is in `redaxo/src/core/lib/fragment.php`

Always reference these instructions first and fallback to search or bash commands only when you encounter unexpected information that does not match the info here.

## Working Effectively

### Bootstrap and Install Dependencies
- Install PHP dependencies: `composer install --prefer-dist --no-progress --no-interaction`
  - Takes 17-50 seconds depending on network speed. NEVER CANCEL. Set timeout to 180+ seconds.
  - May require authentication against GitHub. If you encounter authentication errors, set up Composer authentication by creating a GitHub token and running `composer config --global github-oauth.github.com <your-token>`. Alternatively, you can try `composer install --no-dev` to reduce the number of packages fetched.
  - Installs PHP-CS-Fixer and related tools to `vendor/` directory (37 packages)

### Code Quality and Validation
- Check PHP syntax of project files only (recommended for routine validation): `find . -maxdepth 3 -name "*.php" -not -path "./vendor/*" -exec php -l {} \;`
  - Takes approximately 2 seconds for 56 project PHP files. Should report "No syntax errors detected" for all files.
  - To check all files including vendor (comprehensive, slower): `find . -name "*.php" -exec php -l {} \;`
  - Takes approximately 98 seconds for all 2520 PHP files (including vendor). Use only for full validation or troubleshooting.
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

#### Key Directories and File Structure
- `boot.php` - executed on every page load for addon initialization
- `install.php` - executed during addon installation
- `uninstall.php` - executed during addon uninstallation  
- `update.php` - executed when updating the addon via REDAXO installer
- `lib/` - directory containing PHP classes for the addon functionality
- `lib/yform/value/` - Field type implementations (26 custom field types)
- `lib/yform/validate/` - Form validation implementations  
- `lib/yform/action/` - Form action implementations
- `assets/` - Compiled CSS, JavaScript, and SVG assets
- `scss/` - SCSS source files for styling
- `ytemplates/` - YForm template files for field rendering
- `pages/` - REDAXO backend interface pages for data management and configuration
- `fragments/` - HTML fragment templates (output templates) written in Bootstrap 5
- `install/` - Directory containing additional installation files required for functionality
- `lang/` - Language files for internationalization

#### Main Files
- `boot.php` - Addon bootstrap and initialization
- `package.yml` - Addon metadata and requirements (includes version number)
- `composer.json` - PHP dependencies (PHP-CS-Fixer)
- `.php-cs-fixer.dist.php` - Code style configuration

#### CI and Publishing
- `.github/workflows/code-style.yml` - Automated code style checking
- `.github/workflows/publish-to-redaxo-org.yml` - Automated publishing on release

## Development Workflows

### Development Best Practices and Rules

When developing functionality and solving bugs, follow these guidelines:

1. **Code Quality Standards**
   - Avoid inline CSS and inline JavaScript - if required, use nonce attributes
   - Use PHP best practices including type hinting and proper typing
   - Follow REDAXO 5 coding standards via `redaxo/php-cs-fixer-config`

2. **Compatibility and Design**
   - Consider backward compatibility for core functions and functionality changes (not just bugfixes)
   - Avoid overly complex solutions - prefer short, concise solutions appropriate to the task
   - Only work with more complexity if specifically requested or after 3-4 minutes if no good solution is found

3. **Version Management**
   - In PRs, increment the `version` number in `package.yml` according to Semantic Versioning
   - Determine if changes are: bugfix/patch, minor update with new features, or major update without backward compatibility
   - Propose the next version number based on the main repository state

4. **Issue Handling and Requirements**
   - Before developing functionality or fixing bugs, verify that necessary information has been provided
   - Required information includes: stack trace, reproduction steps, affected area/method as starting point
   - If information is missing or unclear, stop and ask clarifying questions to narrow the scope
   - Describe your planned approach before proceeding

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
- No traditional build process - PHP files are executed directly (not available via composer, yarn, or other package managers)
- SCSS compilation happens during REDAXO addon installation
- No automated testing - requires manual validation in REDAXO
- Focus on field types for forms, not general web application features
- Code can be tested directly without a build process
- The addon extends YForm functionality specifically, not general REDAXO features