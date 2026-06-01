CREATE TABLE `tx_vdlada_domain_model_health_network` (
	`email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`www` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdlada_domain_model_housing` (
	`address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	`city` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`contact_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`contact_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`contact_telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`detail_file` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`health_network` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`housing_type` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`images` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`lada_number` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`near_ems` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	`owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`request_form` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`service_provider` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`zip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdlada_domain_model_housing_type` (
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdlada_domain_model_locality` (
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);
