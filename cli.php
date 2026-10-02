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

$dbConfig = require __DIR__ . '/config/database.php';

$database = new App\Core\Database(
    $dbConfig['host'],
    $dbConfig['port'],
    $dbConfig['database'],
    $dbConfig['username'],
    $dbConfig['password']
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
        exit(0);

    case 'admin:create':
        $userRepository = new App\Repositories\UserRepository(
            $database->connection()
        );

        $username = trim((string) readline('Username: '));
        $email = trim((string) readline('Email: '));

        $password = promptHidden('Password: ');

        if ($username === '') {
            fwrite(STDERR, "\nUsername cannot be empty.\n");
            exit(1);
        }

        if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            fwrite(STDERR, "\nPlease enter a valid email address.\n");
            exit(1);
        }

        if ($password === '') {
            fwrite(STDERR, "\nPassword cannot be empty.\n");
            exit(1);
        }

        if (strlen($password) < 8) {
            fwrite(
                STDERR,
                "\nPassword must be at least 8 characters long.\n"
            );
            exit(1);
        }

        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        if ($passwordHash === false) {
            fwrite(STDERR, "\nUnable to hash password.\n");
            exit(1);
        }

        try {
            if ($userRepository->countAdmins() > 0) {
                fwrite(
                    STDERR,
                    "\nAn admin account already exists.\n"
                );
                exit(1);
            }

            $userId = $userRepository->createAdmin(
                $username,
                $email,
                $passwordHash
            );

            echo "\nAdmin account created successfully.\n";
            echo "Admin ID: {$userId}\n";
        } catch (PDOException $exception) {
            fwrite(
                STDERR,
                "\nUnable to create admin account: "
                    . $exception->getMessage()
                    . "\n"
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
            fwrite(
                STDERR,
                "No administrator account found.\n"
            );
            exit(1);
        }

        if (count($admins) > 1) {
            fwrite(
                STDERR,
                "Multiple administrator accounts found. Password change aborted.\n"
            );
            exit(1);
        }

        $admin = $admins[0];

        $password = promptHidden('New password: ');
        $passwordConfirmation = promptHidden(
            'Confirm new password: '
        );

        if ($password === '') {
            fwrite(STDERR, "\nPassword cannot be empty.\n");
            exit(1);
        }

        if (strlen($password) < 8) {
            fwrite(
                STDERR,
                "\nPassword must be at least 8 characters long.\n"
            );
            exit(1);
        }

        if ($password !== $passwordConfirmation) {
            fwrite(
                STDERR,
                "\nPasswords do not match.\n"
            );
            exit(1);
        }

        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        if ($passwordHash === false) {
            fwrite(STDERR, "\nUnable to hash password.\n");
            exit(1);
        }

        try {
            $userRepository->updatePassword(
                (int) $admin['id'],
                $passwordHash
            );

            echo "\nAdministrator password updated successfully.\n";
        } catch (PDOException $exception) {
            fwrite(
                STDERR,
                "\nUnable to update administrator password: "
                    . $exception->getMessage()
                    . "\n"
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
            fwrite(
                STDERR,
                "No administrator account found.\n"
            );
            exit(1);
        }

        if (count($admins) > 1) {
            fwrite(
                STDERR,
                "Multiple administrator accounts found. Password reset aborted.\n"
            );
            exit(1);
        }

        $admin = $admins[0];

        echo "Administrator account: {$admin['username']}\n";

        $email = trim(readline('Admin email: '));

        if ($email === '') {
            fwrite(
                STDERR,
                "Email cannot be empty.\n"
            );
            exit(1);
        }

        if (strcasecmp($email, $admin['email']) !== 0) {
            fwrite(
                STDERR,
                "Administrator email does not match.\n"
            );
            exit(1);
        }

        $password = promptHidden('New password: ');
        $passwordConfirmation = promptHidden(
            'Confirm new password: '
        );

        if ($password === '') {
            fwrite(STDERR, "\nPassword cannot be empty.\n");
            exit(1);
        }

        if (strlen($password) < 8) {
            fwrite(
                STDERR,
                "\nPassword must be at least 8 characters long.\n"
            );
            exit(1);
        }

        if ($password !== $passwordConfirmation) {
            fwrite(
                STDERR,
                "\nPasswords do not match.\n"
            );
            exit(1);
        }

        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        if ($passwordHash === false) {
            fwrite(STDERR, "\nUnable to hash password.\n");
            exit(1);
        }

        try {
            $userRepository->resetAdminPassword(
                $admin['email'],
                $passwordHash
            );

            echo "\nAdministrator password reset successfully.\n";
        } catch (PDOException $exception) {
            fwrite(
                STDERR,
                "\nUnable to reset administrator password: "
                    . $exception->getMessage()
                    . "\n"
            );
            exit(1);
        }

        exit(0);

    default:
        fwrite(
            STDERR,
            "Unknown command: {$command}\n"
        );
        exit(1);
}
