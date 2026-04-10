CREATE TABLE `tx_vdsmallads_domain_model_smallads` (
	`cat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`cat2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`comment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`content` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`crdateexternal` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`displayemail` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	`email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`image` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`iscommercial` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`reviewed` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	`slug` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`vdexternaluid` int(10) UNSIGNED NOT NULL DEFAULT 0
);
