<?php
declare(strict_types=1);

final class Resources
{
    public function getActiveResources(): array
    {
        $statement = Database::connection()->prepare(
            'SELECT 
                r.*,
                wc.NAME AS category_name
             FROM resources r
             LEFT JOIN wellness_categories wc 
                ON r.category_id = wc.id
             WHERE r.is_active = 1
             ORDER BY r.created_at DESC'
        );

        $statement->execute();

        $result = $statement->get_result();

        $resources = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($resources as &$resource) {
            $resource['services'] = array_filter(
                array_map('trim', explode("\n", (string) $resource['services']))
            );
        }
        unset($resource);

        $statement->close();

        return $resources;
    }


    public function getAllResources(): array
    {
        $statement = Database::connection()->prepare(
            'SELECT 
                r.*,
                wc.NAME AS category_name
             FROM resources r
             LEFT JOIN wellness_categories wc
                ON r.category_id = wc.id
             ORDER BY r.created_at DESC'
        );

        $statement->execute();

        $result = $statement->get_result();

        $resources = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($resources as &$resource) {
            $resource['services'] = array_filter(
                array_map('trim', explode("\n", (string) $resource['services']))
            );
        }
        unset($resource);

        $statement->close();

        return $resources;
    }

    public function searchResources(string $keyword, bool $activeOnly = false): array
{
    $sql = '
        SELECT 
            r.*,
            wc.NAME AS category_name
        FROM resources r
        LEFT JOIN wellness_categories wc
            ON r.category_id = wc.id
        WHERE 
            (
                r.title LIKE ?
                OR r.description LIKE ?
                OR wc.NAME LIKE ?
            )
    ';


    if ($activeOnly) {
        $sql .= ' AND r.is_active = 1 ';
    }


    $sql .= ' ORDER BY r.created_at DESC';


    $statement = Database::connection()->prepare($sql);


    $search = '%' . $keyword . '%';


    $statement->bind_param(
        'sss',
        $search,
        $search,
        $search
    );


    $statement->execute();


    $result = $statement->get_result();


    $resources = $result->fetch_all(MYSQLI_ASSOC);



    foreach ($resources as &$resource) {

        $resource['services'] = array_filter(
            array_map(
                'trim',
                explode("\n", (string)$resource['services'])
            )
        );

    }

    unset($resource);


    $statement->close();


    return $resources;
}

    public function createResource(
        int $categoryId,
        string $title,
        string $description,
        string $services,
        string $email,
        string $phone,
        string $location
    ): bool {
        $statement = Database::connection()->prepare(
            'INSERT INTO resources
        (category_id, title, description, services, email, phone, location, is_active, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW())'
        );


        $statement->bind_param(
            'issssss',
            $categoryId,
            $title,
            $description,
            $services,
            $email,
            $phone,
            $location
        );


        $success = $statement->execute();

        $statement->close();

        return $success;
    }

    public function getCategories(): array
    {
        $statement = Database::connection()->prepare(
            'SELECT id, NAME 
         FROM wellness_categories
         ORDER BY NAME ASC'
        );

        $statement->execute();

        $result = $statement->get_result();

        $categories = $result->fetch_all(MYSQLI_ASSOC);

        $statement->close();

        return $categories;
    }

    public function updateResource(
        int $id,
        string $title,
        string $description,
        string $services,
        string $email,
        string $phone,
        string $location
    ): bool {


        $statement = Database::connection()->prepare(
            'UPDATE resources
        SET 
        title=?,
        description=?,
        services=?,
        email=?,
        phone=?,
        location=?,
        updated_at=NOW()
        WHERE id=?'
        );


        $statement->bind_param(
            "ssssssi",
            $title,
            $description,
            $services,
            $email,
            $phone,
            $location,
            $id
        );


        $success = $statement->execute();

        $statement->close();

        return $success;

    }

    public function deleteResource(int $id): bool
    {
        $statement = Database::connection()->prepare(
            'DELETE FROM resources WHERE id=?'
        );

        $statement->bind_param(
            "i",
            $id
        );

        $success = $statement->execute();

        $statement->close();

        return $success;
    }
}