CREATE TABLE `be_users` (
	`tx_vdcontributors_atevid` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`tx_vdcontributors_service` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`tx_vdcontributors_supercontrib` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
	`tx_vdcontributors_vd_userid` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`tx_vdcontributors_web_adviser` int(10) UNSIGNED NOT NULL DEFAULT 0
);

CREATE TABLE `tt_address` (
	`full_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`parent` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`tx_vdaddrgeneral_case_postale` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`tx_vdaddrgeneral_categoryid1` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`tx_vdaddrgeneral_categoryid2` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`tx_vdaddrgeneral_categoryid3` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`tx_vdaddrgeneral_categoryid4` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`tx_vdaddrgeneral_categoryid5` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`tx_vdaddrgeneral_categoryid6` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`tx_vdlisteprofessionsdb_schoolacronym` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`tx_vdlisteprofessionsdb_schoolname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`tx_vdttaddressextprofilmonichien_profil` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`www` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tt_address_group` (
	`parent_group` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdcontactdb_contact` (
	`address` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`attachedfile` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`comments` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`company` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`localite` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`mobile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`npa` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vddptservices_departments` (
	`code` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vddptservices_services` (
	`code` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`department_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdfilesdb_category` (
	`description` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`link` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdfilesdb_file` (
	`categoryid` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`categoryid2` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`categoryid3` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`categoryid4` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`categoryid5` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`categoryid6` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`datedocument` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`description` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`filepath` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`filepathbyref1` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`filepathbyref2` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`filepathbyref3` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`keywords` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`link` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`vdmunicipalitydistrictid` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`vdmunicipalityid` text COLLATE utf8mb4_unicode_ci NOT NULL
);

CREATE TABLE `tx_vdfilesdbsecri_category` (
	`description` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdfilesdbsecri_file` (
	`addressee` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`categoryid` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`datedocument` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`description` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`filepath` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdlisteprofessionsdb_domain` (
	`domain` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdlisteprofessionsdb_job` (
	`afp` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
	`cfc` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
	`domain` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`dualjob` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
	`fulltime` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
	`learningtime` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`link` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`professionalmaturity` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
	`school` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`synonyms` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdlisteprofessionsdb_job_domain_mm` (
	`uid_local` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`uid_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`tablenames` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`sorting` int(10) UNSIGNED NOT NULL DEFAULT 0,
	KEY `uid_local` (`uid_local`),
	KEY `uid_foreign` (`uid_foreign`)
);

CREATE TABLE `tx_vdlisteprofessionsdb_job_link_mm` (
	`uid_local` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`uid_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`tablenames` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`sorting` int(10) UNSIGNED NOT NULL DEFAULT 0,
	KEY `uid_local` (`uid_local`),
	KEY `uid_foreign` (`uid_foreign`)
);

CREATE TABLE `tx_vdlisteprofessionsdb_job_school_mm` (
	`uid_local` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`uid_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`tablenames` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`sorting` int(10) UNSIGNED NOT NULL DEFAULT 0,
	KEY `uid_local` (`uid_local`),
	KEY `uid_foreign` (`uid_foreign`)
);

CREATE TABLE `tx_vdttaddressextprofilmonichien_profil` (
	`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_news_domain_model_news` (
	`event_end_date` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`vd_display_date_in_title` tinyint(1) UNSIGNED NOT NULL DEFAULT 1
);
