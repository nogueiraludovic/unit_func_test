CREATE TABLE `pages` (
	`tx_vdmunicipalitiessearch_districts` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`tx_vdmunicipalitiessearch_institution` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`tx_vdmunicipalitiessearch_municipalities` text COLLATE utf8mb4_unicode_ci NOT NULL
);

CREATE TABLE `tx_vdmunicipalities_districts` (
	`id_district` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdmunicipalitiessearch_institutions` (
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdmunicipalities_municipalities` (
	`id_district` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`id_municipality` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`id_state` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`idex2000` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`localites` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`name_lower` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`name_lower15` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`name_upper` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`name_upper15` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`npa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`objectid` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`surface` int(10) UNSIGNED NOT NULL DEFAULT 0
);
