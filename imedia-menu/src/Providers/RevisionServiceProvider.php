<?php

declare(strict_types=1);

namespace IMedia\Menu\Providers;

use IMedia\Menu\Contracts\ServiceProvider;
use IMedia\Menu\Database\RevisionRepository;
use IMedia\Menu\Database\PanelRepository;

final class RevisionServiceProvider implements ServiceProvider
{
    private RevisionRepository $revisionRepo;
    private PanelRepository $panelRepo;

    public function register(): void
    {
        $this->revisionRepo = new RevisionRepository();
        $this->panelRepo    = new PanelRepository();
    }

    public function boot(): void
    {
        add_action('imedia_menu_panel_saved', [$this, 'onPanelSaved'], 10, 1);
        add_action('before_delete_post', [$this, 'onMenuItemDeleted'], 10, 2);
    }

    public function onPanelSaved(int $menuItemId): void
    {
        $panel = $this->panelRepo->findByMenuItem($menuItemId);

        if (!$panel || empty($panel->id)) {
            return;
        }

        $this->revisionRepo->create(
            (int) $panel->id,
            $menuItemId,
            $panel->config ?? [],
            $panel->styles ?? null,
            get_current_user_id()
        );
    }

    public function onMenuItemDeleted(int $postId, \WP_Post $post): void
    {
        if ($post->post_type !== 'nav_menu_item') {
            return;
        }

        $this->panelRepo->delete($postId);
        $this->revisionRepo->deleteByPanel($postId);
    }
}
