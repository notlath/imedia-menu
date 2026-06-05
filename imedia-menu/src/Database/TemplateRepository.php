<?php

declare(strict_types=1);

namespace IMedia\Menu\Database;

final class TemplateRepository
{
    private string $table;

    public function __construct()
    {
        global $wpdb;
        $this->table = $wpdb->prefix . Schema::TEMPLATES_TABLE;
    }

    public function findAll(): array
    {
        global $wpdb;

        $rows = $wpdb->get_results(
            "SELECT * FROM {$this->table} ORDER BY name ASC"
        );

        return array_map([$this, 'hydrate'], $rows);
    }

    public function findById(int $id): ?object
    {
        global $wpdb;

        $row = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$this->table} WHERE id = %d",
                $id
            )
        );

        return $row ? $this->hydrate($row) : null;
    }

    public function create(array $data): ?int
    {
        global $wpdb;

        $result = $wpdb->insert($this->table, [
            'name'        => sanitize_text_field($data['name']),
            'description' => isset($data['description']) ? sanitize_textarea_field($data['description']) : null,
            'config'      => wp_json_encode($data['config'] ?? []),
            'styles'      => isset($data['styles']) ? wp_json_encode($data['styles']) : null,
            'meta'        => isset($data['meta']) ? wp_json_encode($data['meta']) : null,
        ]);

        return $result ? (int) $wpdb->insert_id : null;
    }

    public function update(int $id, array $data): bool
    {
        global $wpdb;

        $fields = [];

        if (isset($data['name'])) {
            $fields['name'] = sanitize_text_field($data['name']);
        }

        if (isset($data['description'])) {
            $fields['description'] = sanitize_textarea_field($data['description']);
        }

        if (isset($data['config'])) {
            $fields['config'] = wp_json_encode($data['config']);
        }

        if (isset($data['styles'])) {
            $fields['styles'] = wp_json_encode($data['styles']);
        }

        if (isset($data['meta'])) {
            $fields['meta'] = wp_json_encode($data['meta']);
        }

        if (empty($fields)) {
            return false;
        }

        return $wpdb->update($this->table, $fields, ['id' => $id]) !== false;
    }

    public function delete(int $id): bool
    {
        global $wpdb;

        return $wpdb->delete($this->table, ['id' => $id]) !== false;
    }

    private function hydrate(object $row): object
    {
        $row->config = json_decode($row->config, true);
        $row->styles = $row->styles ? json_decode($row->styles, true) : null;
        $row->meta   = $row->meta ? json_decode($row->meta, true) : null;

        return $row;
    }
}
