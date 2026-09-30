USE portfolio;
-- =========================================================
-- PROFILE
-- =========================================================
INSERT INTO profile (
        id,
        full_name,
        headline,
        bio,
        skills,
        experience,
        education
    )
VALUES (
        1,
        'Alex Purification',
        'Software Developer',
        'I’m a Computer Science & Engineering graduate and Full Stack Developer focused on building database-driven web applications, management systems, API-integrated applications, and custom web tooling.I enjoy understanding how systems work from the ground up — from database design and backend logic to routing, authentication, APIs, frontend interfaces, and browser-side rendering.',
        'PHP, MySQL, JavaScript, HTML, CSS',
        'Add your professional experience here.',
        'Add your education here.'
    );
-- =========================================================
-- CATEGORIES
-- =========================================================
INSERT INTO categories (name, slug)
VALUES ('Web Development', 'web-development'),
    ('Frontend', 'frontend'),
    ('Backend', 'backend'),
    ('Full Stack', 'full-stack'),
    ('Other', 'other');
-- =========================================================
-- TECHNOLOGIES
-- =========================================================
INSERT INTO technologies (name, slug)
VALUES ('HTML', 'html'),
    ('CSS', 'css'),
    ('JavaScript', 'javascript'),
    ('PHP', 'php'),
    ('MySQL', 'mysql'),
    ('React', 'react'),
    ('Vue', 'vue'),
    ('Angular', 'angular'),
    ('Svelte', 'svelte');
-- =========================================================
-- SETTINGS
-- =========================================================
INSERT INTO settings (setting_key, setting_value)
VALUES ('site_title', 'My Portfolio'),
    ('site_description', 'Personal portfolio website'),
    ('github_url', ''),
    ('linkedin_url', ''),
    ('facebook_url', ''),
    ('instagram_url', ''),
    ('twitter_url', ''),
    ('cv_path', '');