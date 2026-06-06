<?php

declare(strict_types=1);

namespace IMedia\Menu\Contracts;

interface Cacheable {

	public function get( string $key ): mixed;

	public function set( string $key, mixed $data, int $duration = 3600 ): bool;

	public function delete( string $key ): bool;

	public function flush(): bool;
}
