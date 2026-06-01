CREATE TABLE `pages` (
	`google_tags_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`vd_hide_menu_button` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
	`vd_nav_legend` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`vd_show_menu` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
);

CREATE TABLE `tt_content` (
	`cards` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`carousel_items` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`content_element_link` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`content_element_link_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`limit` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`related_links` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`related_records` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`services_domain` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`services_theme` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdsite_domain_model_card` (
	`icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`image` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`link` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`parent` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`text` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdsite_domain_model_carouselitem` (
	`image` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`link` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`parent` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`text` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdsite_domain_model_link` (
	`parent` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`uri` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdsite_queueitem` (
	`uri` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);
