CREATE TABLE `tx_vdojvencheres_domain_model_item` (
	`address` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`brand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`canceled` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	`charge_state` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`conditions` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`description` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`district` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`documents` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`expertise_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`expertise_report` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`item_categories` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`item_conditions` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`item_sub_categories` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`kilometer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`model` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`municipality` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`observations` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`parcel_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`path_segment` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`pictures` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`rooms_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`sale` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sale_conditions_file` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`surface` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`year` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`zip_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	FULLTEXT KEY RECHERCHE (`brand`,`city`,`color`,`description`,`district`,`model`,`municipality`,`name`)
);

CREATE TABLE `tx_vdojvencheres_domain_model_itemcategory` (
	`keywords` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`parent` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`selectable` smallint(5) UNSIGNED NOT NULL DEFAULT 0
);

CREATE TABLE `tx_vdojvencheres_domain_model_itemcondition` (
	`description` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`item_categories` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdojvencheres_domain_model_lot` (
	`items` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`sale` int(10) UNSIGNED NOT NULL DEFAULT 0
);

CREATE TABLE `tx_vdojvencheres_domain_model_office` (
	`address` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`page_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`postal_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`zip_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdojvencheres_domain_model_sale` (
	`address` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`discount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`exposure` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`main_office` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`observations` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`park_information` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`place` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`po_box` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`pub_date` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`room` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`sale_categories` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sale_conditions` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sale_date` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sale_items` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`secondary_office` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`status` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`zip_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdojvencheres_domain_model_salecategory` (
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdojvencheres_domain_model_salecondition` (
	`description` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`sale_categories` int(10) UNSIGNED NOT NULL DEFAULT 0
);

CREATE TABLE `tx_vdojvencheres_domain_model_saledate` (
	`hour` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`sale` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sale_date` int(10) UNSIGNED NOT NULL DEFAULT 0
);

CREATE TABLE `tx_vdojvencheres_item_sale_mm` (
	`uid_local` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`uid_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	KEY `uid_local` (`uid_local`),
	KEY `uid_foreign` (`uid_foreign`)
);

CREATE TABLE `tx_vdojvencheres_itemcondition_itemcategory_mm` (
	`uid_local` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`uid_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	KEY `uid_local` (`uid_local`),
	KEY `uid_foreign` (`uid_foreign`)
);

CREATE TABLE `tx_vdojvencheres_lot_item_mm` (
	`uid_local` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`uid_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	KEY `uid_local` (`uid_local`),
	KEY `uid_foreign` (`uid_foreign`)
);

CREATE TABLE `tx_vdojvencheres_sale_officeecondary_office_mm` (
	`uid_local` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`uid_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	KEY `uid_local` (`uid_local`),
	KEY `uid_foreign` (`uid_foreign`)
);

CREATE TABLE `tx_vdojvencheres_salecondition_salecategory_mm` (
	`uid_local` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`uid_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	KEY `uid_local` (`uid_local`),
	KEY `uid_foreign` (`uid_foreign`)
);
