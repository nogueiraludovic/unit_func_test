CREATE TABLE `tx_vdpressreleases_domain_model_contact` (
	`department` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`function` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`name` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	`phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`service` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdpressreleases_domain_model_link` (
	`fieldname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`press_release` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`url` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdpressreleases_domain_model_partnersource` (
	`description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	`logo` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdpressreleases_domain_model_pressrelease` (
	`additional_contents` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`additional_text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	`anonymize` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	`anonymize_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`body_text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	`contacts` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`date_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`files` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`forcedpdf_file` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`images` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`keywords` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	`partner_source_infos` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`partner_sources` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`path_segment` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`publidoc_files` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`signature` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`source_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`subtitle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`summary` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`type` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`videos` int(10) UNSIGNED NOT NULL DEFAULT 0
);

CREATE TABLE `tx_vdpressreleases_pressrelease_contact_mm` (
	`uid_local` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`uid_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	KEY `uid_local` (`uid_local`),
	KEY `uid_foreign` (`uid_foreign`)
);

CREATE TABLE `tx_vdpressreleases_pressrelease_partnersource_mm` (
	`uid_local` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`uid_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	KEY `uid_local` (`uid_local`),
	KEY `uid_foreign` (`uid_foreign`)
);

CREATE TABLE `tx_vdpressreleases_domain_model_pressreleasetype` (
	`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);
