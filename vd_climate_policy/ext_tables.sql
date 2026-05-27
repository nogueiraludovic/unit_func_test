CREATE TABLE `tx_vdclimatepolicy_domain_model_address` (
	`address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	`bodytext` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	`city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`logo` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`axis` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`dicateries` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`peccs` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`themes` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`www` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`zip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdclimatepolicy_domain_model_axis` (
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdclimatepolicy_domain_model_theme` (
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdclimatepolicy_domain_model_dicastery` (
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdclimatepolicy_domain_model_pecc` (
	`color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
);
