<?php

declare(strict_types=1);

return new class {
    public function up(PDO $database): void
    {
        // Default profile.
        $database->exec(
            "INSERT INTO profile (
                id,
                full_name,
                headline,
                bio,
                skills,
                experience,
                education
            )
            SELECT
                1,
                'Alex Purification',
                'Software Developer',
                'I’m a Computer Science & Engineering graduate and Full Stack Developer focused on building database-driven web applications, management systems, API-integrated applications, and custom web tooling.I enjoy understanding how systems work from the ground up — from database design and backend logic to routing, authentication, APIs, frontend interfaces, and browser-side rendering.',
                'PHP, MySQL, JavaScript, HTML, CSS',
                'Add your professional experience here.',
                'Add your education here.'
            WHERE NOT EXISTS (
                SELECT 1
                FROM profile
                WHERE id = 1
            )"
        );

        // Default categories.
        $categories = [
            ['Web Development', 'web-development'],
            ['Frontend', 'frontend'],
            ['Backend', 'backend'],
            ['Full Stack', 'full-stack'],
            ['Other', 'other'],
        ];

        $categoryStatement = $database->prepare(
            'INSERT INTO categories (
                name,
                slug
            )
            SELECT
                :name,
                :slug
            WHERE NOT EXISTS (
                SELECT 1
                FROM categories
                WHERE name = :existing_name
                   OR slug = :existing_slug
            )'
        );

        foreach ($categories as [$name, $slug]) {
            $categoryStatement->execute([
                'name' => $name,
                'slug' => $slug,
                'existing_name' => $name,
                'existing_slug' => $slug,
            ]);
        }

        // Default technologies.
        $technologies = [
            ['HTML', 'html'],
            ['CSS', 'css'],
            ['JavaScript', 'javascript'],
            ['PHP', 'php'],
            ['MySQL', 'mysql'],
            ['React', 'react'],
            ['Vue', 'vue'],
            ['Angular', 'angular'],
            ['Svelte', 'svelte'],
        ];

        $technologyStatement = $database->prepare(
            'INSERT INTO technologies (
                name,
                slug
            )
            SELECT
                :name,
                :slug
            WHERE NOT EXISTS (
                SELECT 1
                FROM technologies
                WHERE name = :existing_name
                   OR slug = :existing_slug
            )'
        );

        foreach ($technologies as [$name, $slug]) {
            $technologyStatement->execute([
                'name' => $name,
                'slug' => $slug,
                'existing_name' => $name,
                'existing_slug' => $slug,
            ]);
        }

        // Default settings.
        $settings = [
            ['site_title', 'My Portfolio'],
            ['site_description', 'Personal portfolio website'],
            ['github_url', ''],
            ['linkedin_url', ''],
            ['facebook_url', ''],
            ['instagram_url', ''],
            ['twitter_url', ''],
            ['cv_path', ''],
        ];

        $settingStatement = $database->prepare(
            'INSERT INTO settings (
                setting_key,
                setting_value
            )
            SELECT
                :setting_key,
                :setting_value
            WHERE NOT EXISTS (
                SELECT 1
                FROM settings
                WHERE setting_key = :existing_key
            )'
        );

        foreach ($settings as [$key, $value]) {
            $settingStatement->execute([
                'setting_key' => $key,
                'setting_value' => $value,
                'existing_key' => $key,
            ]);
        }
    }

    public function down(PDO $database): void
    {
        throw new RuntimeException(
            'Cannot roll back the default seed migration.'
        );
    }
};
