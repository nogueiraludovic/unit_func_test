CREATE TABLE `tx_vdclimatepolicy_domain_model_address` (
	`axis` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`bodytext` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	`cities` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`dicastery` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`logo` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`pecc` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`theme` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`www` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
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
