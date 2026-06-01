CREATE TABLE `pages` (
	`service_contact` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	`service_contact_hidden` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	`service_contact_hidden_subpages` smallint(5) UNSIGNED NOT NULL DEFAULT 0
);

CREATE TABLE `tx_vdcontactservice_domain_model_department` (
	`code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`reference_page` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`services` int(10) UNSIGNED NOT NULL DEFAULT 0
);

CREATE TABLE `tx_vdcontactservice_domain_model_service` (
	`code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`department` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`reference_page` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`service_contacts` int(10) UNSIGNED NOT NULL DEFAULT 0
);

CREATE TABLE `tx_vdcontactservice_domain_model_servicecontact` (
	`account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`additional_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`additional_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`address` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`email_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`email_display_type` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`image` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`internet_fax` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`link` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`link_label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`locality` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`person_function` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`person_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`postal_box` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`postal_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`service` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`summary` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);
