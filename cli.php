<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

function promptHidden(string $prompt): string
{
    echo $prompt;

    if (PHP_OS_FAMILY === 'Windows') {
        $command = 'powershell -NoProfile -Command "$password = Read-Host -AsSecureString; $ptr = [Runtime.InteropServices.Marshal]::SecureStringToBSTR($password); try { [Runtime.InteropServices.Marshal]::PtrToStringBSTR($ptr) } finally { [Runtime.InteropServices.Marshal]::ZeroFreeBSTR($ptr) }"';

        $password = shell_exec($command);

        if ($password === null) {
            throw new RuntimeException(
                'Unable to read hidden password input.'
            );
        }

        return rtrim($password, "\r\n");
    }

    shell_exec('stty -echo');
    $password = fgets(STDIN);
    shell_exec('stty echo');

    echo PHP_EOL;

    if ($password === false) {
        throw new RuntimeException(
            'Unable to read password input.'
        );
    }

    return rtrim($password, "\r\n");
}

function cliError(string $message): void
{
    $stderr = fopen('php://stderr', 'w');

    if ($stderr === false) {
        return;
    }

    fwrite(
        $stderr,
        "\033[31m{$message}\033[0m\n"
    );

    fclose($stderr);
}

function cliSuccess(string $message): void
{
    echo "\033[32m{$message}\033[0m\n";
}

$dbConfig = require __DIR__ . '/config/database.php';

$database = new App\Core\Database(
    $dbConfig['host'],
    $dbConfig['port'],
    $dbConfig['database'],
    $dbConfig['username'],
    $dbConfig['password']
);

$migrationRunner = new App\Core\MigrationRunner(
    $database->connection(),
    new App\Repositories\MigrationRepository(
        $database->connection()
    ),
    __DIR__ . '/database/migrations'
);

$migrationCreator = new App\Core\MigrationCreator(
    __DIR__ . '/database/migrations'
);

$command = $argv[1] ?? null;

if ($command === null) {
    echo "Portfolio CLI\n";
    echo "Usage: php cli.php <command>\n";
    exit(0);
}

switch ($command) {
    case 'help':
        echo "Portfolio CLI\n";
        echo "\n";
        echo "Available commands:\n";
        echo "  help          Show this help message\n";
        echo "  admin:create  Create the administrator account\n";
        echo "  admin:list    List administrator accounts\n";
        echo "  admin:password  Change the administrator password\n";
        echo "  admin:reset-password  Reset the administrator password\n";
        echo "  db:test       Test the database connection\n";
        echo "  migrate       Run pending database migrations\n";
        echo "  migrate:create Create a new migration file\n";
        echo "  migrate:status  Show migration status\n";
        echo "  migrate:rollback Roll back the last migration batch\n";
        exit(0);

    case 'db:test':
        try {
            $database->connection()->query('SELECT 1');

            echo "Database connection: OK\n";
        } catch (PDOException $exception) {
            cliError(
                "Database connection: FAILED"
            );

            exit(1);
        }

        exit(0);

    case 'admin:create':
        $userRepository = new App\Repositories\UserRepository(
            $database->connection()
        );

        $username = trim((string) readline('Username: '));
        $email = trim((string) readline('Email: '));

        $password = promptHidden('Password: ');

        if ($username === '') {
            cliError("\nUsername cannot be empty.");
            exit(1);
        }

        if (
            $email === ''
            || filter_var($email, FILTER_VALIDATE_EMAIL) === false
        ) {
            cliError("\nPlease enter a valid email address.");
            exit(1);
        }

        if ($password === '') {
            cliError("\nPassword cannot be empty.");
            exit(1);
        }

        if (strlen($password) < 8) {
            cliError(
                "\nPassword must be at least 8 characters long."
            );
            exit(1);
        }

        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        if ($passwordHash === false) {
            cliError("\nUnable to hash password.");
            exit(1);
        }

        try {
            if ($userRepository->countAdmins() > 0) {
                cliError(
                    "\nAn admin account already exists."
                );
                exit(1);
            }

            $userId = $userRepository->createAdmin(
                $username,
                $email,
                $passwordHash
            );

            cliSuccess("\nAdmin account created successfully.");
            echo "Admin ID: {$userId}\n";
        } catch (PDOException $exception) {
            cliError(
                "\nUnable to create admin account: "
                    . $exception->getMessage()
            );
            exit(1);
        }

        exit(0);

    case 'admin:list':
        $userRepository = new App\Repositories\UserRepository(
            $database->connection()
        );

        $admins = $userRepository->findAdmins();

        if ($admins === []) {
            echo "No administrator accounts found.\n";
            exit(0);
        }

        echo "Administrator accounts:\n";
        echo "\n";

        foreach ($admins as $admin) {
            echo "ID: {$admin['id']}\n";
            echo "Username: {$admin['username']}\n";
            echo "Email: {$admin['email']}\n";
            echo "Role: {$admin['role']}\n";
            echo "Created: {$admin['created_at']}\n";
            echo "Updated: {$admin['updated_at']}\n";
            echo "\n";
        }

        exit(0);

    case 'admin:password':
        $userRepository = new App\Repositories\UserRepository(
            $database->connection()
        );

        $admins = $userRepository->findAdmins();

        if ($admins === []) {
            cliError(
                "No administrator account found."
            );
            exit(1);
        }

        if (count($admins) > 1) {
            cliError(
                "Multiple administrator accounts found. "
                    . "Password change aborted."
            );
            exit(1);
        }

        $admin = $admins[0];

        $password = promptHidden('New password: ');
        $passwordConfirmation = promptHidden(
            'Confirm new password: '
        );

        if ($password === '') {
            cliError("\nPassword cannot be empty.");
            exit(1);
        }

        if (strlen($password) < 8) {
            cliError(
                "\nPassword must be at least 8 characters long."
            );
            exit(1);
        }

        if ($password !== $passwordConfirmation) {
            cliError("\nPasswords do not match.");
            exit(1);
        }

        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        if ($passwordHash === false) {
            cliError("\nUnable to hash password.");
            exit(1);
        }

        try {
            $userRepository->updatePassword(
                (int) $admin['id'],
                $passwordHash
            );

            cliSuccess(
                "\nAdministrator password updated successfully."
            );
        } catch (PDOException $exception) {
            cliError(
                "\nUnable to update administrator password: "
                    . $exception->getMessage()
            );
            exit(1);
        }

        exit(0);

    case 'admin:reset-password':
        $userRepository = new App\Repositories\UserRepository(
            $database->connection()
        );

        $admins = $userRepository->findAdmins();

        if ($admins === []) {
            cliError(
                "No administrator account found."
            );
            exit(1);
        }

        if (count($admins) > 1) {
            cliError(
                "Multiple administrator accounts found. "
                    . "Password reset aborted."
            );
            exit(1);
        }

        $admin = $admins[0];

        echo "Administrator account: {$admin['username']}\n";

        $email = trim(readline('Admin email: '));

        if ($email === '') {
            cliError(
                "Email cannot be empty."
            );
            exit(1);
        }

        if (strcasecmp($email, $admin['email']) !== 0) {
            cliError(
                "Administrator email does not match."
            );
            exit(1);
        }

        $password = promptHidden('New password: ');
        $passwordConfirmation = promptHidden(
            'Confirm new password: '
        );

        if ($password === '') {
            cliError("\nPassword cannot be empty.");
            exit(1);
        }

        if (strlen($password) < 8) {
            cliError(
                "\nPassword must be at least 8 characters long."
            );
            exit(1);
        }

        if ($password !== $passwordConfirmation) {
            cliError("\nPasswords do not match.");
            exit(1);
        }

        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        if ($passwordHash === false) {
            cliError("\nUnable to hash password.");
            exit(1);
        }

        try {
            $userRepository->resetAdminPassword(
                $admin['email'],
                $passwordHash
            );

            cliSuccess(
                "\nAdministrator password reset successfully."
            );
        } catch (PDOException $exception) {
            cliError(
                "\nUnable to reset administrator password: "
                    . $exception->getMessage()
            );
            exit(1);
        }

        exit(0);

    case 'migrate':
        try {
            $executed = $migrationRunner->migrate();

            if ($executed === 0) {
                echo "No pending migrations.\n";
            } else {
                cliSuccess(
                    "Migrations executed: {$executed}"
                );
            }
        } catch (Throwable $exception) {
            cliError(
                "Migration failed: "
                    . $exception->getMessage()
            );

            exit(1);
        }

        exit(0);

    case 'migrate:create':
        $migrationName = $argv[2] ?? '';

        if (trim($migrationName) === '') {
            cliError(
                "Migration name is required."
            );

            exit(1);
        }

        try {
            $migrationPath = $migrationCreator->create(
                $migrationName
            );

            cliSuccess(
                "Migration created: {$migrationPath}"
            );
        } catch (Throwable $exception) {
            cliError(
                "Migration creation failed: "
                    . $exception->getMessage()
            );

            exit(1);
        }

        exit(0);

    case 'migrate:status':
        $migrationRepository = new App\Repositories\MigrationRepository(
            $database->connection()
        );

        $migrationRepository->ensureTable();

        $migrationFiles = $migrationRepository->migrationFiles(
            __DIR__ . '/database/migrations'
        );

        if ($migrationFiles === []) {
            echo "No migration files found.\n";
            exit(0);
        }

        foreach ($migrationFiles as $file) {
            $migrationName = pathinfo(
                $file,
                PATHINFO_FILENAME
            );

            $status = $migrationRepository->exists(
                $migrationName
            )
                ? 'Applied'
                : 'Pending';

            echo "{$status}: {$migrationName}\n";
        }

        exit(0);

    case 'migrate:rollback':
        try {
            $rolledBack = $migrationRunner->rollback();

            if ($rolledBack === 0) {
                echo "No migrations to roll back.\n";
            } else {
                cliSuccess(
                    "Migrations rolled back: {$rolledBack}"
                );
            }
        } catch (Throwable $exception) {
            cliError(
                "Migration rollback failed: "
                    . $exception->getMessage()
            );

            exit(1);
        }

        exit(0);

    default:
        cliError(
            "Unknown command: {$command}"
        );

        exit(1);
}
