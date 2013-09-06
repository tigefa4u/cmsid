DROP TABLE IF EXISTS `_installer_prefix_menu`;

---

CREATE TABLE IF NOT EXISTS `_installer_prefix_menu` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `class` varchar(255) NOT NULL DEFAULT '',
  `position` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `group_id` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

---

INSERT INTO `_installer_prefix_menu` (`id`, `parent_id`, `title`, `url`, `class`, `position`, `group_id`) VALUES
(1, 0, 'Home', '\/', 'home', 1, 1),
(2, 0, 'Home', '\/', '', 1, 2),
(3, 0, 'Example App', '\/?com=example', '', 2, 1);

---

DROP TABLE IF EXISTS `_installer_prefix_menu_group`;

---

CREATE TABLE IF NOT EXISTS `_installer_prefix_menu_group` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

---

INSERT INTO `_installer_prefix_menu_group` (`id`, `title`) VALUES
(1, 'Menu Utama'),
(2, 'Menu Header');

---

DROP TABLE IF EXISTS `_installer_prefix_options`;

---

CREATE TABLE IF NOT EXISTS `_installer_prefix_options` (
  `option_name` varchar(68) NOT NULL,
  `option_value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

---

INSERT INTO `_installer_prefix_options` (`option_name`, `option_value`) VALUES
('template', 'bloe'),
('sitename', '_installer_sitename_'),
('sitedescription', 'Keterangan dari website'),
('sitekeywords', 'keyword website'),
('admin_email', '_installer_admin_email_'),
('site_public', '1'),
('site_charset', 'UTF-8'),
('siteurl', '_installer_siteurl_'),
('active_plugins', ''),
('siteslogan', 'slogan website'),
('avatar_default', 'mystery'),
('html_type', 'text/html'),
('menu-action', '[''aksi'':{''posts'':{''title'':''Post'',''link'':''?action=post''},''pages'':{''title'':''Pages'',''link'':''?action=pages''}}]'),
('timezone', '_installer_timezone_'),
('site_copyright', '2012 | CMS ID'),
('feed-news', '{"news_feeds":{"News Feed cmsid.org":"http://cmsid.org/rss.xml"},"display":{"desc":0,"author":0,"date":0,"limit":30}}'),
('datetime_format', 'Y/m/d'),
('date_format', 'F j, Y'),
('avatar_default', 'mystery'),
('author', '_installer_author_'),
('post_comment', '1'),
('rewrite', 'advance'),
('body_layout', 'left'),
('dashboard_widget', '{"normal":"dashboard_update_info,dashboard_recent_registration,dashboard_feed_news,","side":"dashboard_quick_post,"}'),
('use_smilies', '1'),
('security_pip', '[{"file":"comment_on_post","ip":"::1","time":13604101830}]'),
('avatar_type', 'computer'),
('image_allaw', '{"image\\/png":".png","image\\/x-png":".png","image\\/gif":".gif","image\\/jpeg":".jpg","image\\/pjpeg":".jpg"}'),
('file_allaw', '["txt","csv","htm","html","xml","css","doc","xls","rtf","ppt","pdf","swf","flv","avi","wmv","mov","jpg","jpeg","gif","png"]'),
('sidebar_widgets', '{"sidebar-1":["pages","meta","archives"],"sidebar-2":["categories"]}'),
('sidebar_actions', ''),
('account_registration', '1'),
('post_comment_filter','1');

---

DROP TABLE IF EXISTS `_installer_prefix_post`;

---

CREATE TABLE IF NOT EXISTS `_installer_prefix_post` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(80) NOT NULL,
  `date_post` datetime NOT NULL,
  `title` text NOT NULL,
  `content` longtext NOT NULL,
  `mail` varchar(160) NOT NULL,
  `post_topic` bigint(20) NOT NULL,
  `hits` int(11) NOT NULL,
  `tags` varchar(225) NOT NULL,
  `sefttitle` varchar(200) NOT NULL,
  `type` varchar(20) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `thumb` longtext NOT NULL,
  `thumb_desc` text NOT NULL,
  `approved` int(1) NOT NULL DEFAULT '0',
  `meta_keys` text NOT NULL,
  `meta_desc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

---

INSERT INTO `_installer_prefix_post` (`id`, `user_login`, `date_post`, `title`, `content`, `mail`, `post_topic`, `hits`, `tags`, `sefttitle`, `type`, `status`, `thumb`, `thumb_desc`, `approved`, `meta_keys`, `meta_desc`) VALUES
(1, 'admin', '2013-03-22 06:42:55', 'Hallo Semua!', '<p>Selamat datang di CMS ID. Ini adalah tulisan pertama Anda. Sunting atau hapus, kemudian mulai membuat artikel!&nbsp;<span id="pastemarkerend">&nbsp;</span><br>\r\n\r\n</p>\r\n', 'id.hpaherba@yahoo.co.id', 1, 602, '', 'hallo-semua', 'post', 1, '', '', 1, '', ''),
(2, 'admin', '2000-07-19 00:00:00', 'Sample Page', '<p>Ini adalah contoh halaman. Yang berbeda dari tulisan karena\r\nakan menjadi satu kesatuan dan akan tampil pada menu navigasi situs (tema). Kebanyakan\r\norang memulai halamannya dengan menuliskan tentang mereka kenalkan ke\r\npengunjung situs. Kata katanya mungkin seperti ini:</p>\r\n\r\n\r\n<blockquote>Hi semua! Saya memiliki pesan hari ini, ini adalah situs\r\nsaya. Saya tinggal di Bandar Lampung, Indonesia, memiliki keluarga yang sangat\r\nhebat, memiliki kucing bernama Miaw, dan saya suka sekali dengan permainan bulu\r\ntangkis dan bola voli</blockquote>\r\n\r\nAtau bisa seperti ini:<br>\r\n\r\n\r\n<blockquote>Perusahaan tanpa nama XYZ didirikan pada tahun 1971, dan\r\ntelah menyediakan jasa informasi berkualitas kepada publik sampai saat ini. Terletak\r\ndi Kota Jakarta, XYZ memperkerjakan lebih dari 10000 karyawan dan melakukkan\r\nsegala macam hal yang mengagumkan bagi masyarakat sekitar.</blockquote>\r\n\r\n<p>Sebagai pengguna&nbsp;<a href="http://cmsid.org/">cmsid</a>&nbsp;yang baru, Anda harus pergi ke\r\ndashboard posting artikel untuk menghapus halaman ini dan mulai membuat halaman\r\nbaru untuk konten Anda. Have fun!. </p>\r\n', 'id.hpaherba@yahoo.co.id', 0, 0, '', 'sample-page', 'page', 1, '', '', 1, '', ''),
(3, 'admin', '2013-03-24 13:01:28', 'Support Us', '<p>Kelangsungan ketersediaan widget dan layanan web situs&nbsp;ini tergantung kepada bantuan dan dukungan dari anda. Banyak cara untuk mewujudkan dukungan tersebut.</p>\r\n<p>&nbsp;</p>\r\n<p><strong>Beritahu Yang Lain!</strong></p>\r\n<p>Silakan gunakan satu atau dua (atau lebih)&nbsp;pada situs atau blog yang anda punyai. Jika anda menggunakan&nbsp;twitter,&nbsp;atau tweet-ulang tulisan-tulisan kami. Jika anda adalah penggemar&nbsp;facebook, jadilah salah satu penggemar Halaman Fan kami ataupun juga jika anda menggunakan Goolgle+. Klik pada tombol&nbsp;Like&nbsp;pada kolom sisi kanan halaman ini.</p>\r\n<p>&nbsp;</p>\r\n<p><strong>Berikan Sumbangan!</strong></p>\r\n<p>Setiap sumbangsih finansial anda, sebesar apapun akan sangat berarti bagi pembayaran hosting situs dan pengelolaannya.</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>Donation bisa ditransfer ke rekening kami di:</p>\r\n<p><strong>BRI UNIT SIDOMULYO TELUK BETUNG&nbsp;</strong></p>\r\n<p><strong>No. Rek. 3562-01-016475-53-9&nbsp;</strong></p>\r\n<p><strong>a.n. Eko Hendratno</strong></p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>Kami memohon kepada Allah atas petunjuk dan pertolongan-Nya serta limpahan rizqi yang terbaik.</p>', 'id.hpaherba@yahoo.co.id', 0, 0, '', 'support-us', 'page', 1, '', '', 1, '', ''),
(4, 'admin', '2013-03-24 15:14:46', 'Cerita dibalik pengembangan CMS ID v 2.2', '<p><span style="line-height: 1.5em;">CMS ID versi 2.2 adalah platform cms, pengembangan dari\r\ncmsid versi sebelumnya.</span><br>\r\n\r\n</p>\r\n\r\n<p>Cmsid pada versi ini sebagian merupakan platform cms code\r\nturunan dari codex wordpress dan digabungkan dengan platform cmsid itu sendiri\r\ndengan cita rasa kedua belah pihak kami selaku pengembang menggunakan hal hal\r\nyang ada platform cms tersebut untuk diterapkan pada cmsid versi ini.</p>\r\n\r\n<p>Cmsid versi ini sendiri merupakan versi yang beda dari versi\r\nversi sebelumnya dikarenakan kami melakukkan penelitian yang cukup panjang\r\nuntuk meneliti bagaimana sebuah platform cms popular saat ini yaitu wordpress\r\nitu bekerja, lalu kami menuangkannya pada cmsid versi baru ini,.. tentunya\r\ndengan beberapa sintax code yang kami bawa pada versi ini. Tapi tidak selamanya\r\nkami menggunakan syntax code itu suatu saat saat pengembangan cmsid lebih jauh\r\nmungkin cmsid akan mengadaptasikan synatax codenya sendiri cepat atau lambat.</p>\r\n\r\n<p>Kenapa kami memilih platform cms wordpress sebagain patner\r\ncodex, ini bukan lain ialah kemudahan pembuatan content yang lebih cepat dan\r\nmudah terutama dalam penggunaannya dengan begitu akan makin cepat dan mudah\r\ncontent akan tersaji untuk digunakan.</p>\r\n\r\n<p><b><br>\r\n\r\n</b></p>\r\n\r\n<p><b>Siapa saja dibalik pengembangan cmsid dan siapa siapa saja\r\nyang telah mendukung,..</b></p>\r\n<p><b><br>\r\n</b></p>\r\n\r\n<p>Saya akan menceritakan kembali sejarah cmsid,. CMS ID di\r\nkembangkan dan didirikan pada tahun 2010 tepatnya pada bulan april dahulu kala\r\ncmsid mesih belum menggunakan domain resmi cmsid.org dahulu domain name cmsid\r\nmasih menggunakan domain gratis yg banyak bertebaran diinternet&nbsp; sampai suatu saat ada supporter yg mendukung\r\ncmsid sampai saat ini, anda bisa cari dan lihat hostname dari cmsid itu sendiri\r\ndan tidak lain dan bukan ialah dutaspace.com lalu siapa dibalik dutaspace.com\r\nitu ialah Sdr.Hadi Mahmud ia adalah reseller hosting yg mendukung penuh cmsid\r\nsampai saat ini, lalu siapa juga yg dibalik pengembangan cmsid itu bukan lain\r\ndan bukan ialah Sdr. Eko seorang Mahasiswa Fakultas Teknik Informatik IBI\r\nDarmajaya Bandar Lampung serta teman teman yang telah memberikan kritik dan\r\nsarannya.</p>\r\n\r\n<p><b><br>\r\n\r\n</b></p>\r\n\r\n<p><b>Kemana arah cmsid itu,..?</b></p>\r\n<p><b><br>\r\n</b></p>\r\n\r\n<p>Yang pastinya cmsid ingin menjadi salah satu platform cms\r\nyang dicintai disisi penggunanya, cmsid&nbsp;\r\nakan selalu terus dan terus dikembangkan &nbsp;dan cmsid juga berencana ingin membuat sebuah produk\r\nbuku ulasan pengembangan cmsid dan waktunya tak dapat saya ditentukan sekarang,\r\nkarena ini merupakan kesiapan disisi penulisan saya, tapi rencana ini sudah\r\nsaya pikir matang matang untuk melaksanakannya, itupun kalau ada yg berkanan\r\nmencicipi buku ini,.. </p>\r\n<p><br>\r\n</p>\r\n\r\n<p>Baiklah itu adalah sepenggal cerita dari saya dibalik\r\npengembangan cmsid ini, jika ada saran dan kritik silahkan kirim ke form form\r\nyang kami sediakan bisa juga melalui forum&nbsp;\r\natau group group kami di fb: <a href="https://www.facebook.com/groups/cmsid/">https://www.facebook.com/groups/cmsid/</a> &nbsp;</p>\r\n\r\n<p>Jika anda salah satu yang berniat bergabung sebagai\r\npengembang atau supporter kami sailahkan hubungi saya di email:id.hpaherba@yahoo.co.id</p>\r\n\r\n<p>Itu sekian prakata dari saya kurang dan lebihnya saya mohon\r\nmaaf,.. salam id by eko</p>\r\n', 'id.hpaherba@yahoo.co.id', 1, 5, 'cerita, cmsid', 'cerita-dibalik-pengembangan-cms-id-v-2-2', 'post', 1, '', '', 1, '', '');

---

DROP TABLE IF EXISTS `_installer_prefix_post_comment`;

---

CREATE TABLE IF NOT EXISTS `_installer_prefix_post_comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` text NOT NULL,
  `author` varchar(30) NOT NULL,
  `email` varchar(90) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time` int(20) NOT NULL,
  `post_id` int(11) NOT NULL DEFAULT '0',
  `comment_parent` int(11) NOT NULL,
  `approved` int(1) NOT NULL DEFAULT '1',
  `user_id` varchar(80) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

---

INSERT INTO `iw_post_comment` (`comment_id`, `comment`, `author`, `email`, `date`, `time`, `post_id`, `comment_parent`, `approved`, `user_id`) VALUES
(1, 'Hai, ini adalah komentar.<br />\r\nUntuk menghapus sebuah komentar, cukup masuk log dan lihat komentar tulisan tersebut. Di sana Anda akan memiliki pilihan untuk mengedit atau menghapusnya.', 'Eko Azza', 'id.hpaherba@yahoo.co.id', '2013-03-24 23:32:35', 1364167955, 1, 0, 1, 'admin');

---

DROP TABLE IF EXISTS `_installer_prefix_post_topic`;

---

CREATE TABLE IF NOT EXISTS `_installer_prefix_post_topic` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `topic` varchar(100) NOT NULL,
  `desc` text NOT NULL,
  `public` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

---

INSERT INTO `_installer_prefix_post_topic` (`id`, `topic`, `desc`, `public`, `status`) VALUES
(1, 'Sebuah kategori', 'Keterangan Sebuah kategori', 0, 1);

---

DROP TABLE IF EXISTS `_installer_prefix_users`;

---

CREATE TABLE IF NOT EXISTS `_installer_prefix_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL,
  `user_author` varchar(80) NOT NULL,
  `user_pass` varchar(64) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_sex` enum('p','l') NOT NULL,
  `user_registered` datetime NOT NULL,
  `user_last_update` datetime NOT NULL,
  `user_activation_key` varchar(60) NOT NULL,
  `user_level` varchar(25) NOT NULL DEFAULT 'user',
  `user_url` varchar(100) NOT NULL,
  `display_name` smallint(250) NOT NULL,
  `user_country` varchar(64) NOT NULL,
  `user_province` varchar(80) NOT NULL,
  `user_avatar` longtext NOT NULL,
  `user_status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;