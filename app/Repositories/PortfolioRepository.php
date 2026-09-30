<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class PortfolioRepository
{
    public function __construct(
        private PDO $database
    ) {}

    public function getAll(): array
    {
        $statement = $this->database->prepare(
            'SELECT
                p.id,
                p.category_id,
                p.title,
                p.slug,
                p.description,
                p.thumbnail,
                p.project_type,
                p.project_path,
                p.entry_path,
                p.external_url,
                p.is_featured,
                p.is_published,
                p.sort_order,
                p.created_at,
                p.updated_at,
                c.name AS category_name
             FROM portfolios p
             INNER JOIN categories c
                ON c.id = p.category_id
             ORDER BY
                p.sort_order ASC,
                p.created_at DESC'
        );

        $statement->execute();

        $portfolios = $statement->fetchAll();

        foreach ($portfolios as &$portfolio) {
            $portfolio['technologies'] =
                $this->getTechnologies(
                    (int) $portfolio['id']
                );
        }

        unset($portfolio);

        return $portfolios;
    }

    public function getPublished(): array
    {
        $statement = $this->database->prepare(
            'SELECT
                p.id,
                p.category_id,
                p.title,
                p.slug,
                p.description,
                p.thumbnail,
                p.project_type,
                p.project_path,
                p.entry_path,
                p.external_url,
                p.is_featured,
                p.is_published,
                p.sort_order,
                p.created_at,
                p.updated_at,
                c.name AS category_name
             FROM portfolios p
             INNER JOIN categories c
                ON c.id = p.category_id
             WHERE p.is_published = 1
             ORDER BY
                p.sort_order ASC,
                p.created_at DESC'
        );

        $statement->execute();

        $portfolios = $statement->fetchAll();

        foreach ($portfolios as &$portfolio) {
            $portfolio['technologies'] =
                $this->getTechnologies(
                    (int) $portfolio['id']
                );
        }

        unset($portfolio);

        return $portfolios;
    }

    public function searchPublished(
        ?string $search = null,
        ?int $categoryId = null,
        ?int $technologyId = null,
        int $page = 1,
        int $perPage = 9,
        string $sort = 'featured'
    ): array {
        $page = max(1, $page);
        $perPage = max(1, $perPage);

        $offset = ($page - 1) * $perPage;

        $conditions = [
            'p.is_published = 1',
        ];

        $parameters = [];

        /*
|--------------------------------------------------------------------------
| Text Search
|--------------------------------------------------------------------------
|
| Search project title, description,
| category name, and technology name.
|
*/

        if (
            $search !== null &&
            trim($search) !== ''
        ) {
            $conditions[] = '(
        p.title LIKE :search_title
        OR p.description LIKE :search_description
        OR c.name LIKE :search_category
        OR EXISTS (
            SELECT 1
            FROM portfolio_technology pt_search
            INNER JOIN technologies t_search
                ON t_search.id = pt_search.technology_id
            WHERE pt_search.portfolio_id = p.id
            AND t_search.name LIKE :search_technology
        )
    )';

            $searchTerm =
                '%' . trim($search) . '%';

            $parameters['search_title'] =
                $searchTerm;

            $parameters['search_description'] =
                $searchTerm;

            $parameters['search_category'] =
                $searchTerm;

            $parameters['search_technology'] =
                $searchTerm;
        }

        /*
|--------------------------------------------------------------------------
| Category Filter
|--------------------------------------------------------------------------
*/

        if ($categoryId !== null) {
            $conditions[] =
                'p.category_id = :category_id';

            $parameters['category_id'] =
                $categoryId;
        }

        /*
|--------------------------------------------------------------------------
| Technology Filter
|--------------------------------------------------------------------------
*/

        if ($technologyId !== null) {
            $conditions[] = 'EXISTS (
        SELECT 1
        FROM portfolio_technology pt_filter
        WHERE pt_filter.portfolio_id = p.id
        AND pt_filter.technology_id = :technology_id
    )';

            $parameters['technology_id'] =
                $technologyId;
        }

        $where =
            implode(
                ' AND ',
                $conditions
            );

        $sortOrder = match ($sort) {
            'newest' => 'p.created_at DESC',
            'oldest' => 'p.created_at ASC',
            'name_asc' => 'p.title ASC',
            'name_desc' => 'p.title DESC',
            default => 'p.is_featured DESC, p.sort_order ASC, p.created_at DESC',
        };

        /*
|--------------------------------------------------------------------------
| Count Matching Projects
|--------------------------------------------------------------------------
*/

        $countStatement =
            $this->database->prepare(
                "SELECT COUNT(*)
         FROM portfolios p
         INNER JOIN categories c
            ON c.id = p.category_id
         WHERE {$where}"
            );

        foreach ($parameters as $key => $value) {
            $countStatement->bindValue(
                ':' . $key,
                $value,
                is_int($value)
                    ? \PDO::PARAM_INT
                    : \PDO::PARAM_STR
            );
        }

        $countStatement->execute();

        $total =
            (int) $countStatement->fetchColumn();

        /*
|--------------------------------------------------------------------------
| Get Projects For Current Page
|--------------------------------------------------------------------------
*/

        $statement =
            $this->database->prepare(
                "SELECT
            p.id,
            p.category_id,
            p.title,
            p.slug,
            p.description,
            p.thumbnail,
            p.project_type,
            p.project_path,
            p.entry_path,
            p.external_url,
            p.is_featured,
            p.is_published,
            p.sort_order,
            p.created_at,
            p.updated_at,
            c.name AS category_name
         FROM portfolios p
         INNER JOIN categories c
            ON c.id = p.category_id
         WHERE {$where}
         ORDER BY {$sortOrder}
        LIMIT :limit
        OFFSET :offset"
            );

        foreach ($parameters as $key => $value) {
            $statement->bindValue(
                ':' . $key,
                $value,
                is_int($value)
                    ? \PDO::PARAM_INT
                    : \PDO::PARAM_STR
            );
        }

        $statement->bindValue(
            ':limit',
            $perPage,
            \PDO::PARAM_INT
        );

        $statement->bindValue(
            ':offset',
            $offset,
            \PDO::PARAM_INT
        );

        $statement->execute();

        $items =
            $statement->fetchAll();

        /*
|--------------------------------------------------------------------------
| Add Technologies
|--------------------------------------------------------------------------
*/

        foreach ($items as &$item) {
            $item['technologies'] =
                $this->getTechnologies(
                    (int) $item['id']
                );
        }

        unset($item);

        /*
|--------------------------------------------------------------------------
| Return Paginated Results
|--------------------------------------------------------------------------
*/

        return [
            'items' => $items,

            'total' => $total,

            'page' => $page,

            'per_page' => $perPage,

            'total_pages' => max(
                1,
                (int) ceil(
                    $total / $perPage
                )
            ),
        ];
    }


    public function findById(int $id): ?array
    {
        $statement = $this->database->prepare(
            'SELECT
                p.id,
                p.category_id,
                p.title,
                p.slug,
                p.description,
                p.thumbnail,
                p.project_type,
                p.project_path,
                p.entry_path,
                p.external_url,
                p.is_featured,
                p.is_published,
                p.sort_order,
                p.created_at,
                p.updated_at,
                c.name AS category_name
             FROM portfolios p
             INNER JOIN categories c
                ON c.id = p.category_id
             WHERE p.id = :id
             LIMIT 1'
        );

        $statement->execute([
            'id' => $id,
        ]);

        $portfolio = $statement->fetch();

        if (!$portfolio) {
            return null;
        }

        $portfolio['technologies'] =
            $this->getTechnologies(
                (int) $portfolio['id']
            );

        return $portfolio;
    }

    public function findBySlug(string $slug): ?array
    {
        $statement = $this->database->prepare(
            'SELECT
                p.id,
                p.category_id,
                p.title,
                p.slug,
                p.description,
                p.thumbnail,
                p.project_type,
                p.project_path,
                p.entry_path,
                p.external_url,
                p.is_featured,
                p.is_published,
                p.sort_order,
                p.created_at,
                p.updated_at,
                c.name AS category_name
             FROM portfolios p
             INNER JOIN categories c
                ON c.id = p.category_id
             WHERE p.slug = :slug
             LIMIT 1'
        );

        $statement->execute([
            'slug' => $slug,
        ]);

        $portfolio = $statement->fetch();

        if (!$portfolio) {
            return null;
        }

        $portfolio['technologies'] =
            $this->getTechnologies(
                (int) $portfolio['id']
            );

        return $portfolio;
    }

    public function create(
        int $categoryId,
        string $title,
        string $slug,
        string $description,
        ?string $thumbnail,
        string $projectType,
        ?string $projectPath,
        ?string $entryPath,
        ?string $externalUrl,
        bool $isFeatured,
        bool $isPublished,
        int $sortOrder,
        array $technologyIds
    ): int {
        $this->database->beginTransaction();

        try {

            $statement = $this->database->prepare(
                'INSERT INTO portfolios
                    (
                        category_id,
                        title,
                        slug,
                        description,
                        thumbnail,
                        project_type,
                        project_path,
                        entry_path,
                        external_url,
                        is_featured,
                        is_published,
                        sort_order
                    )
                 VALUES
                    (
                        :category_id,
                        :title,
                        :slug,
                        :description,
                        :thumbnail,
                        :project_type,
                        :project_path,
                        :entry_path,
                        :external_url,
                        :is_featured,
                        :is_published,
                        :sort_order
                    )'
            );

            $statement->execute([
                'category_id' => $categoryId,
                'title' => $title,
                'slug' => $slug,
                'description' => $description,
                'thumbnail' => $thumbnail,
                'project_type' => $projectType,
                'project_path' => $projectPath,
                'entry_path' => $entryPath,
                'external_url' => $externalUrl,
                'is_featured' => $isFeatured ? 1 : 0,
                'is_published' => $isPublished ? 1 : 0,
                'sort_order' => $sortOrder,
            ]);

            $portfolioId =
                (int) $this->database->lastInsertId();

            $this->syncTechnologies(
                $portfolioId,
                $technologyIds
            );

            $this->database->commit();

            return $portfolioId;
        } catch (\Throwable $e) {

            $this->database->rollBack();

            throw $e;
        }
    }

    public function update(
        int $id,
        int $categoryId,
        string $title,
        string $slug,
        string $description,
        ?string $thumbnail,
        string $projectType,
        ?string $projectPath,
        ?string $entryPath,
        ?string $externalUrl,
        bool $isFeatured,
        bool $isPublished,
        int $sortOrder,
        array $technologyIds
    ): bool {
        $this->database->beginTransaction();

        try {

            $statement = $this->database->prepare(
                'UPDATE portfolios
                 SET
                    category_id = :category_id,
                    title = :title,
                    slug = :slug,
                    description = :description,
                    thumbnail = :thumbnail,
                    project_type = :project_type,
                    project_path = :project_path,
                    entry_path = :entry_path,
                    external_url = :external_url,
                    is_featured = :is_featured,
                    is_published = :is_published,
                    sort_order = :sort_order
                 WHERE id = :id'
            );

            $updated = $statement->execute([
                'id' => $id,
                'category_id' => $categoryId,
                'title' => $title,
                'slug' => $slug,
                'description' => $description,
                'thumbnail' => $thumbnail,
                'project_type' => $projectType,
                'project_path' => $projectPath,
                'entry_path' => $entryPath,
                'external_url' => $externalUrl,
                'is_featured' => $isFeatured ? 1 : 0,
                'is_published' => $isPublished ? 1 : 0,
                'sort_order' => $sortOrder,
            ]);

            $this->syncTechnologies(
                $id,
                $technologyIds
            );

            $this->database->commit();

            return $updated;
        } catch (\Throwable $e) {

            $this->database->rollBack();

            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        $statement = $this->database->prepare(
            'DELETE FROM portfolios
             WHERE id = :id'
        );

        return $statement->execute([
            'id' => $id,
        ]);
    }

    public function getTechnologies(int $portfolioId): array
    {
        $statement = $this->database->prepare(
            'SELECT
                t.id,
                t.name,
                t.slug
             FROM technologies t
             INNER JOIN portfolio_technology pt
                ON pt.technology_id = t.id
             WHERE pt.portfolio_id = :portfolio_id
             ORDER BY t.name ASC'
        );

        $statement->execute([
            'portfolio_id' => $portfolioId,
        ]);

        return $statement->fetchAll();
    }

    public function syncTechnologies(
        int $portfolioId,
        array $technologyIds
    ): void {
        $deleteStatement =
            $this->database->prepare(
                'DELETE FROM portfolio_technology
                 WHERE portfolio_id = :portfolio_id'
            );

        $deleteStatement->execute([
            'portfolio_id' => $portfolioId,
        ]);

        if (empty($technologyIds)) {
            return;
        }

        $insertStatement =
            $this->database->prepare(
                'INSERT INTO portfolio_technology
                    (
                        portfolio_id,
                        technology_id
                    )
                 VALUES
                    (
                        :portfolio_id,
                        :technology_id
                    )'
            );

        foreach ($technologyIds as $technologyId) {

            $insertStatement->execute([
                'portfolio_id' => $portfolioId,
                'technology_id' => (int) $technologyId,
            ]);
        }
    }

    public function slugExists(
        string $slug,
        ?int $exceptId = null
    ): bool {
        if ($exceptId === null) {

            $statement = $this->database->prepare(
                'SELECT id
                 FROM portfolios
                 WHERE slug = :slug
                 LIMIT 1'
            );

            $statement->execute([
                'slug' => $slug,
            ]);
        } else {

            $statement = $this->database->prepare(
                'SELECT id
                 FROM portfolios
                 WHERE slug = :slug
                   AND id != :id
                 LIMIT 1'
            );

            $statement->execute([
                'slug' => $slug,
                'id' => $exceptId,
            ]);
        }

        return $statement->fetch() !== false;
    }
}
