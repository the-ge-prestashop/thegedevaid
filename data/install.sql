CREATE TABLE `PREFIX_thegedevaid` (
  `id_fix` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_hook` int(1) unsigned DEFAULT NULL,
  `position` int(10) unsigned NOT NULL DEFAULT 0,
  `content` text DEFAULT NULL,
  PRIMARY KEY (`id_fix`)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB

CREATE TABLE `PREFIX_thegedevaid_lang` (
  `id_fix` int(10) unsigned NOT NULL,
  `id_lang` int(10) unsigned NOT NULL,
  `description` text DEFAULT NULL,
  INDEX IDX_THEGEDEVAID_LANG_DEVAID (id_fix),
  INDEX IDX_THEGEDEVAID_LANG_LANG (id_lang),
  PRIMARY KEY (`id_fix`,`id_lang`)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB

CREATE TABLE `PREFIX_thegedevaid_shop` (
  `id_fix` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shop` int(10) unsigned NOT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT 0,
  INDEX IDX_THEGEDEVAID_SHOP_DEVAID (id_fix),
  INDEX IDX_THEGEDEVAID_SHOP_SHOP (id_shop),
  PRIMARY KEY (`id_fix`,`id_shop`)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
