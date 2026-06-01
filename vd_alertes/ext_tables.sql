CREATE TABLE `tx_vdalertes_domain_model_home_alert` (
	`color` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	`image` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`link` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`summary`text COLLATE utf8mb4_unicode_ci NOT NULL,
	`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdalertes_domain_model_service_alert` (
	`depth` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	`link` varchar(1024) DEFAULT '' NOT NULL,
	`summary` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);
