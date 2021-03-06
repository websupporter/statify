<?php

/* Quit */
defined( 'ABSPATH' ) OR exit;

/**
 * Statify_Uninstall
 *
 * @since 0.1
 */
class Statify_Uninstall {
	/**
	 * Plugin uninstall handler.
	 *
	 * @since 0.1.0
	 * @change 0.1.0
	 */
	public static function init() {
		global $wpdb;

		if ( is_multisite() ) {
			$old = get_current_blog_id();

			// Todo: Use get_sites() in WordPress 4.6+
			$ids = $wpdb->get_col( "SELECT blog_id FROM `$wpdb->blogs`" );

			foreach ( $ids as $id ) {
				switch_to_blog( $id );
				self::_apply();
			}

			switch_to_blog( $old );
		}

		self::_apply();
	}

	/**
	 * Cleans things up for a deleted site on Multisite.
	 *
	 * @since 1.4.4
	 *
	 * @param int $site_id Site ID.
	 */
	public function init_site( $site_id ) {
		switch_to_blog( $site_id );

		self::_apply();

		restore_current_blog();
	}

	/**
	 * Deletes all plugin data.
	 *
	 * @since 0.1.0
	 * @change 1.4.0
	 */
	private static function _apply() {
		/* Delete options */
		delete_option( 'statify' );

		/* Init table */
		Statify_Table::init();

		/* Delete table */
		Statify_Table::drop();
	}
}