CREATE TABLE `tx_vdpublicationscg_domain_model_document` (
	`files` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`publication_date` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`publisher` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`slug` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
);

CREATE TABLE `tx_vdpublicationscg_domain_model_publisher` (
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);
