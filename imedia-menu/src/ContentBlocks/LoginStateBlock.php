<?php

declare(strict_types=1);

namespace IMedia\Menu\ContentBlocks;

use IMedia\Menu\Contracts\ContentBlock;

final class LoginStateBlock implements ContentBlock {

	/**
	 * Block registry for rendering child blocks in both states.
	 *
	 * @var Registry|null
	 */
	private ?Registry $registry = null;

	public function setRegistry( Registry $registry ): void {
		$this->registry = $registry;
	}

	public function type(): string {
		return 'login_state';
	}

	public function title(): string {
		return __( 'Login State', 'imedia-menu' );
	}

	public function defaultConfig(): array {
		return array(
			'logged_in_blocks'  => array(),
			'logged_out_blocks' => array(),
			'fallback'          => 'empty',
		);
	}

	public function render( array $config, array $styles = array() ): string {
		$loggedIn  = is_user_logged_in();
		$branchKey = $loggedIn ? 'logged_in_blocks' : 'logged_out_blocks';
		$branch    = $config[ $branchKey ] ?? array();

		if ( ! is_array( $branch ) || empty( $branch ) ) {
			if ( ( $config['fallback'] ?? 'empty' ) === 'hide' ) {
				return '';
			}
			return sprintf(
				'<div class="imm-block imm-block--login-state" data-state="%s"></div>',
				$loggedIn ? 'in' : 'out'
			);
		}

		$childrenHtml = '';
		if ( $this->registry !== null ) {
			foreach ( $branch as $childBlock ) {
				if ( is_array( $childBlock ) && isset( $childBlock['type'] ) ) {
					$childrenHtml .= $this->registry->render( $childBlock );
				}
			}
		}

		return sprintf(
			'<div class="imm-block imm-block--login-state" data-state="%s">%s</div>',
			$loggedIn ? 'in' : 'out',
			$childrenHtml
		);
	}
}
