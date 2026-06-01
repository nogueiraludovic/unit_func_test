CREATE TABLE `tx_vdprestations_domain_model_accessmodality` (
	`additional_informations` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`average_delay` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`cost` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`epayment` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	`external_link` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	`hash` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`howto` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`required_documents` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`security_level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`url` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdprestations_domain_model_prestation` (
	`access_modalities` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`action_client` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`action_service` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`description` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`domain_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`domain_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`domain_target_page` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`emolument` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`external_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`help_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`legal_references` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`path_segment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`prerequisites` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`related_pages` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`related_prestations` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`result` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`security` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`service_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`target_audience` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`theme_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`theme_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`theme_target_page` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`title` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdprestations_domain_model_targetaudience` (
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdprestations_domain_model_url` (
	`fieldname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`hash` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`url` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdprestations_prestation_prestation_mm` (
	`uid_local` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`uid_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	KEY `uid_local` (`uid_local`),
	KEY `uid_foreign` (`uid_foreign`)
);
