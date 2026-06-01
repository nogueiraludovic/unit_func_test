CREATE TABLE `tx_vddirectory_domain_model_address` (
	`address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	`bodytext` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	`city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`logo` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`phones` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sector` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`service` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`theme` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`www` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`zip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vddirectory_domain_model_phone` (
	`address` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vddirectory_domain_model_sector` (
	`color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vddirectory_domain_model_service` (
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vddirectory_domain_model_theme` (
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);
