CREATE TABLE `tx_vdocosp_adresses` (
	`adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`adresse_2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`code_postal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`delais` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	`nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`site_web` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`type_adresse` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`ville` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdocosp_classes` (
	`description` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdocosp_diplomes` (
	`diplome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdocosp_dmde` (
	`adresse` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`conditions_admission` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`conditions_admission_2` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`description` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`description_temp` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`diplome_complement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`diplome_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`domaine_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`domaine_id_2` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`external_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`external_link` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`formation` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`formation_2` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`formation_desc` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`hits` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`indemnites` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`interets` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`lien_podcast` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`lieu` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`lieu_2` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`profession` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`profession_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`remarques` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`remarques_2` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`remarques_interne` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`video_zoom` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdocosp_dmde_adresse_mm` (
	`uid_local` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`uid_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`tablenames` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`sorting` int(10) UNSIGNED NOT NULL DEFAULT 0,
	KEY `uid_local` (`uid_local`),
	KEY `uid_foreign` (`uid_foreign`)
);

CREATE TABLE `tx_vdocosp_dmde_interet_mm` (
	`uid_local` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`uid_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting` int(10) UNSIGNED NOT NULL DEFAULT 0,
	KEY `uid_local` (`uid_local`),
	KEY `uid_foreign` (`uid_foreign`)
);

CREATE TABLE `tx_vdocosp_dmde_professions_mm` (
	`uid_local` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`uid_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	KEY `uid_local` (`uid_local`),
	KEY `uid_foreign` (`uid_foreign`)
);

CREATE TABLE `tx_vdocosp_domaines` (
	`delais` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	`demande` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	`nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdocosp_im_domaines` (
	`description` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`profession` int(10) UNSIGNED NOT NULL DEFAULT 0
);

CREATE TABLE `tx_vdocosp_im_domaines_profession_mm` (
	`uid_local` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`uid_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	KEY `uid_local` (`uid_local`),
	KEY `uid_foreign` (`uid_foreign`)
);

CREATE TABLE `tx_vdocosp_inscriptions` (
	`adresse` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`date_examen` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`date_maj` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`delai_inscription` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`diplome` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`domaine` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`formation` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`profession` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`url_exad` int(10) UNSIGNED NOT NULL DEFAULT 0
);

CREATE TABLE `tx_vdocosp_inscriptions_profession_mm` (
	`uid_local` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`uid_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`tablenames` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`sorting` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`sorting_foreign` int(10) UNSIGNED NOT NULL DEFAULT 0,
	KEY `uid_local` (`uid_local`),
	KEY `uid_foreign` (`uid_foreign`)
);

CREATE TABLE `tx_vdocosp_interets` (
	`nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
);

CREATE TABLE `tx_vdocosp_professions` (
	`anciennes_denominations` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`dmde` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`inscriptions` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`mots_cles` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`niveau_cnc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`nom_fem` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`nom_masc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`reglement_abroge` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
	`remarques` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`remarques_ext` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`titre_delivre` int(10) UNSIGNED NOT NULL DEFAULT 0
);
