CREATE TABLE `tt_address` (
	`parent` int(10) UNSIGNED NOT NULL DEFAULT 0
);

CREATE TABLE `tx_vdapprenticeship_apprenticeship` (
	`address` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`available_places` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`dgav_animals` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`dgav_divers` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`dgav_farming_products` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`dgav_language` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`dgav_profession` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`dgav_type` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`particularities` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`region` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`slug` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdapprenticeship_periods` (
	`parent` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdapprenticeship_profession` (
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdapprenticeship_region` (
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdapprenticeship_type` (
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);
