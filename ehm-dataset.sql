-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql_ehm
-- Generation Time: Jul 04, 2025 at 04:39 PM
-- Server version: 9.3.0
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Easy-Hotel-Management`
--

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id_account`, `email`, `password`, `codice_fiscale`, `id_ruolo`) VALUES
(1, 'marco@gmail.com', 'marco', 'MRNSEB04E10F130G', 1),
(2, 'seby@gmail.com', 'seby', 'MRNSEB04E10F130G', 1),
(3, 'daniele@gmail.com', 'daniele', 'DSTDNL103AD30FAD', 2);

--
-- Dumping data for table `camere`
--

INSERT INTO `camere` (`numero_camera`, `id_edificio`, `posti_letto`, `prezzo_notte`) VALUES
('A100', 1, 3, 25),
('A101', 1, 5, 45),
('A102', 1, 6, 75),
('A150', 3, 2, 45),
('A151', 3, 6, 45),
('A153', 3, 3, 45),
('B100', 2, 2, 25),
('B101', 2, 4, 35),
('B103', 2, 5, 55),
('C101', 4, 3, 43),
('C102', 4, 5, 50),
('C103', 4, 4, 65);

--
-- Dumping data for table `edifici`
--

INSERT INTO `edifici` (`id_edificio`, `nome`, `id_hotel`) VALUES
(1, 'Edificio A', 1),
(2, 'Edificio B', 1),
(3, 'Edificio A', 2),
(4, 'Edificio C', 3);

--
-- Dumping data for table `email`
--

INSERT INTO `email` (`email`, `descrizione`, `id_hotel`) VALUES
('oracle@gmail.com', 'Direzione', 1),
('oraclejava@gmail.com', 'Direzione', 3),
('oraclejavaprenotazioni@gmail.com', 'Prenotazioni', 3),
('oraclereception@gmail.com', 'Reception', 1),
('oraclesun@gmail.com', 'Direzione', 2);

--
-- Dumping data for table `fatture`
--

INSERT INTO `fatture` (`id_fattura`, `data_emissione`, `importo_totale`, `numero_carta`, `cvc_carta`, `numero_conto_corrente`, `id_tipo_pagamento`, `id_prenotazione`) VALUES
(1, '2025-07-04', 50, '2323242453535353', 432, NULL, 1, 1),
(2, '2025-07-04', 50, NULL, NULL, NULL, 3, 2),
(3, '2025-07-04', 90, NULL, NULL, NULL, 3, 3),
(4, '2025-07-04', 400, NULL, NULL, NULL, 3, 4),
(5, '2025-07-04', 110, NULL, NULL, '232324242424', 2, 5);

--
-- Dumping data for table `hotel`
--

INSERT INTO `hotel` (`id_hotel`, `nome`, `via`) VALUES
(1, 'Hotel Oracle', 'Via Oracolo 369'),
(2, 'Hotel Oracle Sun', 'Via Sun, 32'),
(3, 'Hotel Oracle Java', 'Via Java, 64');

--
-- Dumping data for table `impieghi_hotel`
--

INSERT INTO `impieghi_hotel` (`id_impiego`, `codice_fiscale`, `id_hotel`) VALUES
(1, 'MRNSEB04E10F130G', 1),
(2, 'BRBMRC04E10F158G', 1),
(3, 'NLMRTE02E136F23F', 2);

--
-- Dumping data for table `mansioni`
--

INSERT INTO `mansioni` (`mansione`, `descrizione`) VALUES
('Bartender', 'Servizio bar'),
('Cuoco', 'Cucine'),
('Inserviente', 'Pulizia camere'),
('Portiere', 'Accoglienza ingresso'),
('Receptionist', 'Addetto alla reception');

--
-- Dumping data for table `mansioni_staff`
--

INSERT INTO `mansioni_staff` (`codice_fiscale`, `mansione`) VALUES
('BRBMRC04E10F158G', 'Receptionist'),
('MRNSEB04E10F130G', 'Bartender'),
('DSTDNL103AD30FAD', 'Inserviente'),
('CLSDAESADA43D234', 'Cuoco'),
('NLMRTE02E136F23F', 'Receptionist'),
('NLMRTE02E136F23F', 'Portiere');

--
-- Dumping data for table `ospiti`
--

INSERT INTO `ospiti` (`codice_fiscale`, `nome`, `cognome`, `eta`, `telefono`, `indirizzo`) VALUES
('BCLCRX33E210CK6Q', 'Gianpaolo', 'Camicione', 38, '+39 369 2691 99', 'Piazza Lopresti, 71, Prelerna'),
('BDRGSX26P02SKK2Z', 'Priscilla', 'Sismondi', 57, '+39 372 1798 48', 'Stretto Bettina, 36 Appartamento 0, Magaro'),
('BGLGTT48H0854TXJ', 'Pierpaolo', 'Borroni', 65, '+39 384 2422 31', 'Strada Martino, 71, Crodo'),
('BLLDLS69S176PZXH', 'Napoleone', 'Galiazzo', 74, '+39 305 8395 77', 'Via Ciampi, 219 Appartamento 4, Sant\'Andrea Di Suasa'),
('BLLPNG33L08MK2XX', 'Micheletto', 'Endrizzi', 33, '+39 358 7843 65', 'Contrada Oreste, 669, Villar Sampeyre'),
('BMBBDS47A23J5G6P', 'Angelica', 'Mazzi', 66, '+39 320 0691 84', 'Viale Massimo, 965, Sevignano'),
('BNDRLF83C19GFGDK', 'Loretta', 'Nicoletti', 33, '+39 328 3770 08', 'Rotonda Galilei, 7, Cuvio'),
('BNGMTN60A0943CXW', 'Maria', 'Greggio', 50, '+39 371 8733 60', 'Via Bettin, 3, Lessolo'),
('BRCRLL10M23KM5SW', 'Amalia', 'Norbiato', 45, '+39 339 5864 61', 'Rotonda Graziano, 445 Appartamento 1, Santa Caterina Di Pittinurri'),
('BRLGXX64E15PF9XC', 'Isa', 'Dibiasi', 60, '+39 313 4145 67', 'Canale Gianfranco, 990, Belluno'),
('BRNNLT40C0828JWV', 'Fiorino', 'Bembo', 81, '+39 328 4544 18', 'Piazza Ivan, 64 Piano 6, Antagnod'),
('BSCBCC53D0523W0L', 'Durante', 'Comisso', 56, '+39 363 4623 36', 'Borgo Giuliano, 7, Spinazzola'),
('BTGMHL04E31K1Y2C', 'Patrizia', 'Cerquiglini', 42, '+39 365 2222 42', 'Canale Giada, 35 Appartamento 1, Terlano'),
('BTNGHN68T31N1PXD', 'Annibale', 'Turci', 62, '+39 314 8508 95', 'Viale Buonauro, 35 Piano 1, Spezzano Albanese Stazione'),
('BTTNLS10P135KMTC', 'Roberto', 'Mascagni', 24, '+39 375 5411 79', 'Incrocio Adele, 565, Angeli'),
('BVXBCC17T31D67RW', 'Victoria', 'Camiscione', 85, '+39 357 4883 17', 'Canale Scarpetta, 167 Appartamento 55, Forino'),
('BZXFTN21B136SDVX', 'Marina', 'Andreotti', 30, '+39 302 0331 17', 'Canale Nicoletta, 5, Preselle'),
('CBBMLX51A110Q1FF', 'Mercedes', 'Giammusso', 26, '+39 386 9672 91', 'Strada Rossana, 661, Ballabio'),
('CBCNTL65T03060TR', 'Vito', 'Cerutti', 88, '+39 331 7745 23', 'Stretto Ferrari, 720 Appartamento 24, Zungri'),
('CBNNNX86B14CF4QX', 'Ferdinando', 'Cugia', 51, '+39 373 4050 55', 'Strada Sabbatini, 27 Piano 7, Benetutti'),
('CCRCCC87E25SBN7L', 'Angelo', 'Saraceno', 42, '+39 384 3143 76', 'Vicolo Gabriella, 277, Bassano Del Grappa'),
('CCRLNX81E27GKMXC', 'Tonia', 'Legnante', 20, '+39 304 5521 83', 'Incrocio Augusto, 64, Pineta Di Sortenna'),
('CCXLZR66S30FTS1R', 'Fulvio', 'Comboni', 67, '+39 366 2496 32', 'Borgo Savorgnan, 3, Lirone'),
('CGNGBB07E2581RNG', 'Alessia', 'Russo', 32, '+39 390 2293 22', 'Stretto Lucio, 4, Chiassa Superiore'),
('CHDLVC10B08BXBND', 'Iolanda', 'Pedrazzini', 41, '+39 334 7462 04', 'Viale Cirillo, 82 Piano 9, Tuglie'),
('CHSRNG36R289TXQL', 'Marco', 'Navarria', 85, '+39 387 7014 59', 'Vicolo Righi, 93, Lisanza'),
('CLGGLL57B1645HCV', 'Melania', 'Bossi', 52, '+39 337 4987 46', 'Rotonda Dante, 12 Appartamento 75, Riozzo'),
('CMBMCL35B08TVCVC', 'Livia', 'Draghi', 67, '+39 329 4842 87', 'Strada Vianello, 3, Magliano In Toscana'),
('CMCRMN91S148WCLW', 'Vincenzo', 'Cabrini', 35, '+39 330 0701 76', 'Via Camillo, 396, Torre San Patrizio'),
('CMMRCT79L30R868Z', 'Marco', 'Campise', 83, '+39 344 6961 81', 'Incrocio Turchetta, 33, Vicovaro'),
('CMNMND82D18F1DDX', 'Sylvia', 'Giacometti', 54, '+39 313 7622 36', 'Stretto Cannizzaro, 123, Sant\'Angelo Lomellina'),
('CMRGTN83B02KTBVQ', 'Calogero', 'Cendron', 32, '+39 377 2038 15', 'Piazza Ilaria, 182, Pietra Ligure'),
('CMSGNT57P2665Y4V', 'Daria', 'Stradivari', 75, '+39 302 9580 13', 'Canale Rastelli, 58, Montecchio'),
('CNNLRZ25S302MYSX', 'Giustino', 'Botticelli', 41, '+39 324 4185 95', 'Rotonda Patrizio, 6 Piano 0, Brenner'),
('CNTCLD98C04GDWKX', 'Antonina', 'Necci', 41, '+39 329 4715 26', 'Canale Cirillo, 0, Capostrada'),
('CNTVNC59M09F5VRH', 'Victoria', 'Pucci', 86, '+39 330 4929 72', 'Piazza Rita, 1, Trassilico'),
('CNVGFR10H19T1Q1R', 'Fernanda', 'Carducci', 54, '+39 302 4552 56', 'Incrocio Malatesta, 4 Piano 5, Rozzano'),
('CNVLRT67D24ZVN7M', 'Alberto', 'Morucci', 21, '+39 339 2527 73', 'Incrocio Rocco, 20, Pompu'),
('CRCMRS05A06TJ5NT', 'Costanzo', 'Tuzzolino', 89, '+39 323 8551 92', 'Rotonda Baldassare, 66, Parrana San Martino'),
('CRDFLV68L06GZ9RN', 'Adriano', 'Galilei', 60, '+39 367 7545 85', 'Viale Trentin, 180, Montagnana Pistoiese'),
('CRDGTN40C21RL4TV', 'Bartolomeo', 'Sagredo', 24, '+39 373 2876 12', 'Vicolo Ruggieri, 48 Piano 7, Mara'),
('CRDMRX15R2979W1T', 'Paride', 'Bertoli', 19, '+39 354 4390 52', 'Borgo Castellitto, 7 Appartamento 1, Trasaghis'),
('CRSGPP36B10Y32ZH', 'Romina', 'Pisano', 25, '+39 338 4383 22', 'Via Temistocle, 24, Dorzano'),
('CRTGZN30D114Q1SV', 'Michele', 'Malacarne', 70, '+39 370 6777 90', 'Vicolo Zaguri, 478 Appartamento 7, Rocca San Felice'),
('CRXGLC72H13KVH3K', 'Valerio', 'Santi', 67, '+39 361 6706 40', 'Vicolo Ennio, 87, Mozzano Torretta'),
('CSCLCX94H16LLS5G', 'Orlando', 'Morpurgo', 38, '+39 337 0537 00', 'Piazza Scamarcio, 64 Appartamento 0, Troviggiano'),
('CSDGTN26A04ZNJRJ', 'Sophia', 'Schiaparelli', 22, '+39 374 6257 60', 'Incrocio Milo, 2, La Fiora'),
('CSNLRC32D16S95HW', 'Federica', 'Nonis', 90, '+39 326 9647 54', 'Viale Gianni, 98, Massa San Giovanni'),
('CSRDRN31B15JRLTB', 'Antonio', 'Babbo', 69, '+39 343 8093 17', 'Via Ivo, 62 Piano 7, Fabrizio'),
('CSTCLL35H02J4KHQ', 'Fausto', 'Munari', 22, '+39 341 2397 42', 'Incrocio Corrado, 380 Piano 4, Trebbo Di Reno'),
('CSTGST04R1288FPL', 'Gioffre', 'Salata', 23, '+39 302 5116 18', 'Via Gabriella, 924 Piano 6, Sticciano Scalo'),
('CTLFNC95P15RMJ2R', 'Ruggero', 'Munari', 33, '+39 308 1680 55', 'Incrocio Bevilacqua, 6, Pignataro Interamna'),
('CVNDRN70T05GY8DR', 'Massimiliano', 'Leoncavallo', 74, '+39 338 6865 35', 'Incrocio Gozzi, 73 Appartamento 34, Farfa'),
('CVNLMX68P08RWYNW', 'Ugo', 'Juvara', 65, '+39 359 5046 94', 'Contrada Villarosa, 0 Piano 7, Badia Pavese'),
('DLLNNZ04T22DRS2S', 'Pierina', 'Sabatini', 36, '+39 307 3335 68', 'Rotonda Filippo, 728 Appartamento 26, Lestans'),
('DNTCLL63S03YJ2NM', 'Berenice', 'Ricciardi', 69, '+39 362 1448 74', 'Rotonda Carla, 82, Monclassico'),
('DNZVCN20R17WPGCK', 'Nicoletta', 'Frescobaldi', 21, '+39 307 1288 61', 'Rotonda Cipriano, 0 Piano 3, Savignano Stazione'),
('DSCMTT57C04ZJ0JB', 'Berenice', 'Gremese', 40, '+39 340 4771 55', 'Viale Fiorino, 152, Antica Colle Piscioso'),
('DTTBTL26M01TM7BK', 'Antonello', 'Casadei', 49, '+39 389 8972 20', 'Piazza Avogadro, 30 Piano 0, Figline Di Prato'),
('DTTRMN74L28J6F2N', 'Arnaldo', 'Badoglio', 36, '+39 384 6638 74', 'Borgo Delle, 935 Piano 5, Montorgiali'),
('FGNRGR53A12CPNVW', 'Sole', 'Donini', 45, '+39 397 6318 77', 'Contrada Trillini, 55 Appartamento 3, Pellegrino'),
('FRMSFN51M01FXW6R', 'Marcella', 'Sgarbi', 71, '+39 312 5423 39', 'Via Melina, 350, Numana'),
('FRRMSM51H15WVHKB', 'Piersanti', 'Valier', 79, '+39 385 6096 22', 'Canale Spinola, 7 Appartamento 1, Marina Di Ascea'),
('FRVGNT19P15PK9CB', 'Annunziata', 'Panicucci', 59, '+39 358 0214 42', 'Strada Fibonacci, 353 Appartamento 93, Pescocanale'),
('FRZSFN93H113073M', 'Romina', 'Barcaccia', 61, '+39 304 1222 75', 'Contrada Cavanna, 95 Piano 7, Pecco'),
('FXXLSN51M0213MPY', 'Rocco', 'Tosi', 62, '+39 398 7851 76', 'Stretto Maura, 16 Piano 5, Vico Canavese'),
('GBBGNN48C10GXC7W', 'Dante', 'Padovano', 68, '+39 349 2602 07', 'Strada Cardano, 26 Appartamento 2, Bressana'),
('GCCPRX99L29C69ZQ', 'Raffaella', 'Montalti', 81, '+39 341 0241 76', 'Contrada Gianmarco, 6 Appartamento 71, San Nazzaro Sesia'),
('GGLDTL10R282D56Y', 'Adelasia', 'Tassoni', 57, '+39 306 5931 94', 'Rotonda Mocenigo, 997, Albiolo'),
('GLTGRC66C20YVT9W', 'Ruggiero', 'Rosiello', 34, '+39 398 0582 57', 'Strada Jolanda, 5 Piano 9, Vighignolo'),
('GNSGNT61S24NHB3S', 'Fiamma', 'Fornaciari', 33, '+39 303 3696 89', 'Via Alderano, 539 Appartamento 4, Ostiano'),
('GNTPQL80M26RTZ5V', 'Emma', 'Trussardi', 58, '+39 388 8215 38', 'Borgo Leonardo, 26, Codigoro'),
('GRFRNZ28H13741NZ', 'Elisa', 'Serlupi', 88, '+39 315 0614 53', 'Piazza Tiziano, 328 Piano 2, Casalvolone'),
('GRGBNR40M30PNVPG', 'Lamberto', 'Longhena', 24, '+39 365 5933 09', 'Via Gabriella, 553, Valpiana'),
('GRGMRT23B073JDGS', 'Donatella', 'Boiardo', 25, '+39 359 7409 11', 'Via Guarana, 33, San Germano Vercellese'),
('GRGRRT43T08N826D', 'Arsenio', 'Lattuada', 65, '+39 374 5635 17', 'Viale Niscoromni, 38 Piano 4, Foiano Di Val Fortore'),
('GVNRLF01L21JXXQG', 'Bianca', 'Paganini', 58, '+39 368 3338 17', 'Strada Varano, 67 Appartamento 84, Sant\'Andrea Frius'),
('GZZRFL50E20LJVJJ', 'Lauretta', 'Oliboni', 75, '+39 320 5776 71', 'Incrocio Giustino, 427, Vigano'),
('JVNGPR99M014JMJZ', 'Pierpaolo', 'Caetani', 74, '+39 337 8388 58', 'Incrocio Victoria, 83, Chesallet Sarre'),
('LBLLHN73M165GT0G', 'Benvenuto', 'Panatta', 32, '+39 337 3041 00', 'Contrada Alfredo, 142, Sestu'),
('LBLMRX50S02RG3FX', 'Lolita', 'Prodi', 54, '+39 354 0232 05', 'Borgo Verdone, 830, Gussola'),
('LBNVTX39P119SGMB', 'Vincentio', 'Cendron', 80, '+39 303 4083 91', 'Borgo Zanazzo, 791, Sant\'Agata'),
('LCNRCR60B08JD90R', 'Pina', 'Saffi', 37, '+39 346 7816 95', 'Piazza Agnesi, 4 Piano 0, Salemi'),
('LDVPQL35H05XZWRK', 'Gemma', 'Lucarelli', 74, '+39 326 3755 34', 'Borgo Ricciotti, 794, Brozzo'),
('LFNNTX98S1272JVM', 'Ottone', 'Zanichelli', 71, '+39 373 5897 73', 'Viale Garibaldi, 87 Piano 4, Recovato'),
('LMBGNT33D02BJH2W', 'Nico', 'Giacometti', 65, '+39 374 5618 87', 'Borgo Zito, 52, Roccanova'),
('LNCFBX99L1419ZGD', 'Sonia', 'Satta', 80, '+39 380 0947 05', 'Stretto Zichichi, 35, Polistena'),
('LNCGSS32D091KGPT', 'Fabrizia', 'Cossiga', 71, '+39 368 9099 00', 'Incrocio Calbo, 594, Borgo Capanne'),
('LNXMCD53S03DZ6SQ', 'Loredana', 'Ajello', 54, '+39 391 1225 55', 'Viale Bondumier, 32 Piano 6, San Michele E Grato'),
('LTTGFR01D226GFQC', 'Paolo', 'Pininfarina', 67, '+39 307 7542 99', 'Rotonda Livia, 4 Piano 8, Chia'),
('LVTPNX51D07GKRLM', 'Liana', 'Coardi', 71, '+39 346 8818 07', 'Vicolo Liberto, 6, St.Jakob In Ahrnta'),
('MCCLHN76E25C85QR', 'Lidia', 'Barberini', 29, '+39 333 7299 12', 'Piazza Gianni, 345 Appartamento 0, Carpineto Della Nora'),
('MCHSVT97B22F9QYL', 'Ugo', 'Moretti', 30, '+39 382 0351 97', 'Borgo Bonaventura, 5, Calvaruso'),
('MCNNNX46H2573K6S', 'Giacinto', 'Renzi', 32, '+39 385 8878 04', 'Viale Bandello, 718 Appartamento 52, Colleferro Stazione'),
('MDGCLD89D10JMVRT', 'Hugo', 'Fuseli', 67, '+39 363 4097 22', 'Stretto Castellitto, 43, Vigolo Vattaro'),
('MDGLCN99T21PHYDK', 'Virgilio', 'Geraci', 81, '+39 335 8256 47', 'Contrada Ceci, 44, Colle Santa Lucia'),
('MDGLNX47R28G7B8F', 'Tonino', 'Cilea', 30, '+39 351 1325 95', 'Canale Marcello, 65, Matonti'),
('MDRNNX58C07M3PFF', 'Raffaele', 'Filogamo', 22, '+39 389 7389 78', 'Incrocio Combi, 56 Piano 5, Biandronno'),
('MLCDRD16A24PJZXD', 'Niccolò', 'Tosi', 70, '+39 383 3556 43', 'Borgo Schicchi, 50 Appartamento 52, Ca\' Corniani'),
('MNCBDT33H20HZX8M', 'Gabriella', 'Baggio', 21, '+39 378 1912 51', 'Canale Bettina, 47, Gambasca'),
('MNCCTN52M02GZXCC', 'Gilberto', 'Luciani', 25, '+39 371 3373 54', 'Vicolo Valentina, 73, Borgo Podgora'),
('MNDMRS23T02X4THY', 'Fiorino', 'Dandolo', 89, '+39 350 7184 49', 'Strada Bertoli, 12, Signa'),
('MNLSDR65C23SMGDN', 'Giacobbe', 'Basadonna', 29, '+39 311 0971 92', 'Incrocio Lombroso, 43 Piano 9, Lama Pezzoli'),
('MNNGRN36T118C7LF', 'Bianca', 'Ovadia', 67, '+39 323 4821 56', 'Borgo Atenulf, 932 Appartamento 75, Passo Del Bocco'),
('MNNGSX41E30MPRFR', 'Ottone', 'Vergerio', 18, '+39 326 1118 96', 'Incrocio Gustavo, 85, Cerveteri'),
('MNNTRS00D255SLXV', 'Annetta', 'Bacosi', 50, '+39 372 3832 65', 'Contrada Fiorenzo, 1 Piano 7, Panetta'),
('MNTCTN17T07KLY5S', 'Severino', 'Saracino', 86, '+39 320 1169 16', 'Canale Lisa, 271 Piano 6, Venegono Superiore'),
('MNTDTL00M011L39D', 'Clelia', 'Caracciolo', 90, '+39 338 5377 11', 'Strada Elena, 56, Pancarana'),
('MNTRSN90B13QBJSM', 'Lucia', 'Contrafatto', 72, '+39 364 9634 41', 'Via Lodovico, 37 Piano 3, Piraino'),
('MNTSVT00B071L5HK', 'Guido', 'Avogadro', 18, '+39 343 2153 06', 'Contrada Persico, 714 Piano 4, Copanello'),
('MRCVLR04A22V2BJW', 'Laura', 'Salvemini', 26, '+39 390 1191 04', 'Incrocio Roman, 14, San Pellegrinetto'),
('MRRGSL33D243L06V', 'Filippa', 'Vezzali', 31, '+39 338 3126 95', 'Stretto Gioacchino, 5, Tribogna'),
('MRSCLL15H02G4SRX', 'Aldo', 'Borgia', 44, '+39 378 9968 97', 'Rotonda Carnera, 79, Limana'),
('MRTFDN64C16L0SFR', 'Calogero', 'Lucchesi', 78, '+39 375 9921 23', 'Stretto Caterina, 2, Assisi'),
('MRTPNX51H06XHB7J', 'Paola', 'Venditti', 82, '+39 329 9272 00', 'Stretto Ricciardi, 262 Appartamento 6, Castelguelfo'),
('MSCGMN66D13CFLBP', 'Nedda', 'Cabibbo', 63, '+39 322 3933 84', 'Vicolo Celentano, 63, Poggio Mirteto'),
('MSTSNT06D06LY4FP', 'Donato', 'Tassoni', 65, '+39 358 4011 24', 'Via Vito, 69 Appartamento 5, Massa Marittima'),
('MZZGDX08M30H8L6Y', 'Lucio', 'Molesini', 64, '+39 355 0321 86', 'Canale Gastone, 57 Appartamento 15, Priacco'),
('MZZMDX34H09RNPSV', 'Marcello', 'Bellò', 58, '+39 310 6865 83', 'Contrada Bramante, 655, Trana'),
('NCHRXX88P28JCJKN', 'Lisa', 'Lucchesi', 54, '+39 371 4978 92', 'Viale Ivan, 5, Arzana'),
('NDRGLR24P06LCDTN', 'Olga', 'Valier', 19, '+39 372 2297 26', 'Stretto Muti, 212 Piano 6, Savignano Di Rigo'),
('NGGMLX62A25NW56K', 'Rolando', 'Ceci', 46, '+39 307 4350 75', 'Contrada Cianciolo, 1, Madonna Di Buja'),
('NGLLLN96H06HG5KD', 'Vanessa', 'Busoni', 50, '+39 300 5788 03', 'Stretto Silvia, 291, Masiera'),
('NGLSLV30A303WS1M', 'Danilo', 'Tartini', 57, '+39 310 4940 55', 'Vicolo Giorgio, 30, Cortenova'),
('NGSMCL88H25BJ4BB', 'Niccolò', 'Tencalla', 30, '+39 341 2441 39', 'Viale Adelmo, 17, Corbezzi'),
('NTNFBX73M08TX5KN', 'Danilo', 'Renault', 43, '+39 370 7395 73', 'Canale Anita, 97, Mazzoleni'),
('NTNRLL65B10X5L4Z', 'Massimo', 'Ferrabosco', 90, '+39 339 4158 76', 'Viale Salvemini, 19 Appartamento 13, Pauli Arbarei'),
('NTNSPH51M24WT9TL', 'Concetta', 'Moschino', 54, '+39 357 6168 09', 'Borgo Mirco, 57, Castelvetro Di Modena'),
('NTTPLN79B27BYX1K', 'Paulina', 'Bova', 68, '+39 393 5434 13', 'Strada Vitturi, 54 Appartamento 40, Semiana'),
('NVRPFL21P25WB7CM', 'Matilda', 'Pisano', 51, '+39 321 7345 67', 'Borgo Paltrinieri, 4, Montauro'),
('PCCLCX55D01FHBRB', 'Giosuè', 'Ubaldi', 21, '+39 340 8317 63', 'Stretto Marissa, 77 Piano 2, Revo\''),
('PCLRFL70L11Z70NQ', 'Gilberto', 'Ferragni', 55, '+39 315 7583 26', 'Contrada Gioffre, 2 Piano 1, Bella'),
('PGNCGR74S13W579F', 'Silvio', 'Bonolis', 70, '+39 350 4827 94', 'Viale Ottone, 39, Melano'),
('PLCSXX61S31PSBSP', 'Donato', 'Petralli', 26, '+39 311 2421 66', 'Contrada Pompeo, 37 Piano 9, Bagnatica'),
('PLTSXX48S13Q8G9C', 'Giulietta', 'Porzio', 38, '+39 312 7316 67', 'Via Innocenti, 87 Piano 4, Champorcher'),
('PNNLTT10T23WW07P', 'Uberto', 'Pasolini', 19, '+39 320 4678 42', 'Contrada Bosio, 94 Piano 9, Casanova Elvo'),
('PNTJND27D28WRY3K', 'Oreste', 'Tutino', 73, '+39 353 8116 52', 'Rotonda Nicoletta, 35 Piano 0, Suni'),
('PNTLTT98R28L8NLR', 'Eraldo', 'Niggli', 81, '+39 322 5342 70', 'Via Liguori, 156 Appartamento 10, Trefontane'),
('PPFTLF76D28M0YGD', 'Eugenia', 'Juvara', 80, '+39 354 3668 46', 'Vicolo Marenzio, 28 Piano 0, Soldano'),
('PRDNCL28P20JFWBK', 'Imelda', 'Pucci', 24, '+39 370 9331 93', 'Vicolo Monduzzi, 72, Cerenova'),
('PRGSXX82E05Q2F3K', 'Marcello', 'Gritti', 71, '+39 336 4172 57', 'Incrocio Melissa, 70 Piano 2, Villafranca Di Verona'),
('PRNTLF13R14Q001X', 'Ivo', 'Marinetti', 34, '+39 346 7228 37', 'Rotonda Lisa, 4 Appartamento 7, Colzate'),
('PRPCRL15B23T8VHN', 'Annibale', 'Antonucci', 26, '+39 332 9795 83', 'Canale Trebbi, 595, La Spezia'),
('PRRLGX16P14Y0QDZ', 'Raffaella', 'Golino', 65, '+39 369 5420 12', 'Rotonda Boezio, 988 Appartamento 33, Salorno'),
('PRTFRG88B29GTBCJ', 'Ninetta', 'Rismondo', 29, '+39 381 1762 28', 'Incrocio Angelica, 4 Piano 0, Monte Romano'),
('PRTMRN08C25Q7PGZ', 'Etta', 'Salvemini', 34, '+39 343 8054 07', 'Piazza Franscini, 666 Piano 5, Cortazzone'),
('PRTRND52C29QD7ZP', 'Amadeo', 'Barberini', 27, '+39 362 5134 89', 'Incrocio Camiscione, 79, Sant\'Anna Avagnina'),
('PRZLDN29A258QN3N', 'Sandra', 'Lollobrigida', 78, '+39 392 9017 77', 'Borgo Antonella, 45, Salice Calabro'),
('PRZMLN44T13CNT3Y', 'Tonia', 'Bonino', 54, '+39 303 3534 95', 'Vicolo Bellocchio, 43 Appartamento 49, Socchieve'),
('PTRMTN27L2871Y7N', 'Melissa', 'Malpighi', 37, '+39 313 9744 77', 'Incrocio Altera, 97, Avezzano'),
('PZZGCH63P14KV76V', 'Ivo', 'Donà', 21, '+39 324 9570 59', 'Contrada Argurio, 45 Appartamento 3, Sparone'),
('RCCDRN07A06BSHQV', 'Virgilio', 'Bixio', 65, '+39 334 3151 26', 'Rotonda Danilo, 6, Sassalbo'),
('RMZMNC03M275JMZN', 'Eliana', 'Smirnoff', 41, '+39 382 4800 77', 'Contrada Donarelli, 85, Castello Di Serravalle'),
('RMZNCL31C2318F4G', 'Adriano', 'Trobbiani', 72, '+39 305 2086 20', 'Canale Berengario, 64 Piano 4, Villaggio Del Pino'),
('RPSLSX13T05VQNXZ', 'Achille', 'Caruso', 74, '+39 379 9428 82', 'Rotonda Boito, 40, Ostiano'),
('RRGTST62M11GVK6Q', 'Rosaria', 'Iacobucci', 42, '+39 308 4826 40', 'Rotonda Blasi, 266 Appartamento 2, Langasco'),
('RSPGLR82D0735S2K', 'Elmo', 'Padovano', 22, '+39 354 6774 21', 'Viale Antonini, 394 Piano 8, Capradosso'),
('RSSNLN27T313GGSY', 'Ruggiero', 'Parini', 86, '+39 371 3016 90', 'Borgo Udinese, 0, Punta Ala'),
('RTCMRS80C13BHTPK', 'Girolamo', 'Asmundo', 81, '+39 342 5337 12', 'Stretto Lazzaro, 62 Piano 2, Villaretto Chisone'),
('RZZMLX92E234WS3M', 'Iolanda', 'Caracciolo', 49, '+39 361 7799 15', 'Canale Giovanna, 574, Somma Vesuviana'),
('SCDLCX97E26LVPVY', 'Jolanda', 'Chinnici', 59, '+39 394 2169 17', 'Stretto Lara, 96, Castiglione Olona'),
('SCHLCN96C217VBXG', 'Fausto', 'Notarbartolo', 60, '+39 368 8022 58', 'Strada Michelangeli, 167, Ceto'),
('SCTMDX76S19DPRCB', 'Panfilo', 'Fattori', 80, '+39 331 4955 57', 'Incrocio Massimo, 14 Appartamento 78, Isola Di Fondra'),
('SGLSFN38L163WJLZ', 'Giada', 'Rosiello', 57, '+39 374 3988 89', 'Canale Romolo, 9 Appartamento 25, Vergnasco'),
('SLRNMR36D09NDH0Y', 'Mercedes', 'Cilibrasi', 48, '+39 365 2814 84', 'Stretto Marcacci, 12, Villaggio Paradiso'),
('SLRPLG24H07V8YTQ', 'Nicolò', 'Dellucci', 32, '+39 323 3933 41', 'Borgo Ceri, 33, Candelu\''),
('SLVGPR24S07W69DL', 'Clelia', 'Turati', 60, '+39 349 1761 48', 'Vicolo Leone, 327, Padenghe Sul Garda'),
('SMTFRZ78B289FYMC', 'Giada', 'Castiglione', 68, '+39 339 5735 80', 'Strada Peano, 60 Appartamento 95, Sillavengo'),
('SPDSLV50D08809VR', 'Massimiliano', 'Olivetti', 41, '+39 375 1525 00', 'Via Matteo, 77, Borgo D\'Ale'),
('SPNLRX64T07ZMXMC', 'Rocco', 'Marinetti', 56, '+39 391 5616 21', 'Incrocio Pistoletto, 713 Piano 2, Dunarobba'),
('SRFCLL46B25JXH6P', 'Lisa', 'Ciani', 42, '+39 351 0463 18', 'Piazza Benito, 628 Appartamento 93, Massascusa'),
('SRNFNC04S305ZTDK', 'Cipriano', 'Loredan', 43, '+39 319 2219 84', 'Canale Nina, 70, Oppeano'),
('SRNFRD68S266B8LL', 'Saverio', 'Vigorelli', 40, '+39 375 9402 07', 'Contrada Dina, 71 Piano 3, Lippo'),
('SSMJND26B1168MSR', 'Greco', 'Berrè', 47, '+39 311 2990 04', 'Rotonda Etta, 90, Aprilia'),
('STCGRC20M19GJGWG', 'Umberto', 'Cattaneo', 47, '+39 375 6584 36', 'Via Pierina, 927 Appartamento 0, Carpineti'),
('SVRRSX35A21D7RCJ', 'Benvenuto', 'Scaramucci', 48, '+39 336 0747 85', 'Strada Flavio, 7, Borrello Di Catania'),
('TLNSDR13M1928BKQ', 'Graziano', 'Lovato', 44, '+39 300 7713 69', 'Borgo Garrone, 61, Marianella'),
('TLRSLL43R06FKMVX', 'Hugo', 'Leonetti', 24, '+39 370 2746 56', 'Stretto Pininfarina, 94 Appartamento 6, Cermignano'),
('TMSPPZ35P174XRPJ', 'Pasquale', 'Barcella', 52, '+39 312 2138 64', 'Vicolo Morena, 5, Balma'),
('TRBNNX06R28HYPRG', 'Cirillo', 'Zoppetto', 60, '+39 389 3947 20', 'Incrocio Camilleri, 6, Villa Di Molvena'),
('TRCDXX72T14MB71X', 'Rossana', 'Polesel', 53, '+39 320 9579 66', 'Via Emma, 67, Sbarre'),
('TRCRFL34M03JV87F', 'Lucio', 'Giunti', 72, '+39 358 6226 34', 'Borgo Duse, 17, Cervicati'),
('TRNDLS61E243T7FZ', 'Rosario', 'Cugia', 43, '+39 366 9975 56', 'Contrada Giacometti, 288 Appartamento 7, Madonna Di Tirano'),
('TRNSLV09S02CSDLK', 'Ludovica', 'Benedetti', 25, '+39 396 1054 43', 'Vicolo Michelangeli, 190, Vermiglio'),
('TRPNLN49T2149PLB', 'Guarino', 'Gilardoni', 68, '+39 390 0705 44', 'Borgo Ernesto, 11, Longi'),
('TRRNTT48R190RQ5Y', 'Rosina', 'Pizziol', 80, '+39 340 2475 66', 'Incrocio Rosario, 5 Piano 8, Nuragheddu'),
('TRTGRG66E20K1R7R', 'Eleanora', 'Correr', 49, '+39 364 4745 34', 'Borgo Cimini, 57 Piano 4, San Pancrazio Parmense'),
('TRTNDD47P10QS4RG', 'Rembrandt', 'Bossi', 33, '+39 353 5652 86', 'Borgo Ruggiero, 7, Gifflenga'),
('TSCSLV26D24VYRNL', 'Victoria', 'Bellò', 58, '+39 353 1712 42', 'Via Mannoia, 33, Traversella'),
('TSSMHL35C13GB56B', 'Natalia', 'Colletti', 81, '+39 311 1851 58', 'Stretto Catalano, 640, San Prisco'),
('TSTTNX52B01461ZW', 'Gioachino', 'Fioravanti', 50, '+39 313 2831 73', 'Incrocio Lando, 93 Piano 2, Vigne Di Narni'),
('TSXFRC57E09QD5SD', 'Vito', 'Geraci', 73, '+39 386 0389 98', 'Incrocio Gigli, 7, Bafia'),
('TSXLVC17A28BGCBT', 'Simonetta', 'Anastasi', 59, '+39 324 4491 25', 'Borgo Letizia, 22 Piano 4, Pra\''),
('VGDCCD16B08XLFVJ', 'Barbara', 'Morlacchi', 53, '+39 332 4244 20', 'Strada Luna, 71 Appartamento 7, Surdo'),
('VGRNNN49C27CKN9G', 'Loretta', 'Versace', 50, '+39 356 1462 88', 'Borgo Fantozzi, 38, Sanluri'),
('VRGLGN64R217960J', 'Melania', 'Taccola', 72, '+39 398 5866 12', 'Stretto Antonio, 966 Appartamento 88, Quinzanello'),
('VRGRML25H0728MJD', 'Costanzo', 'Finotto', 34, '+39 366 4623 06', 'Strada Ferrata, 634, Poggio Rusco'),
('VSNGMR72R22G6XYR', 'Bernardo', 'Bellocchio', 57, '+39 347 4056 64', 'Rotonda Gianni, 613, Motta Sulla Secchia'),
('ZCCTTX48C206PNZX', 'Filippa', 'Tartaglia', 48, '+39 309 8840 08', 'Vicolo Ida, 36 Appartamento 71, Sonico'),
('ZCCZXX77C026P48C', 'Maurilio', 'Catenazzi', 78, '+39 385 7258 11', 'Piazza Correr, 344 Appartamento 7, Castelvecchio Calvisio');

--
-- Dumping data for table `ospiti_prenotazione`
--

INSERT INTO `ospiti_prenotazione` (`id_prenotazione`, `codice_fiscale`) VALUES
(1, 'CSRDRN31B15JRLTB'),
(1, 'BZXFTN21B136SDVX'),
(2, 'PRZMLN44T13CNT3Y'),
(2, 'DTTBTL26M01TM7BK'),
(2, 'ZCCZXX77C026P48C'),
(3, 'TSXLVC17A28BGCBT'),
(3, 'MNNTRS00D255SLXV'),
(3, 'DTTRMN74L28J6F2N'),
(4, 'TRCRFL34M03JV87F'),
(4, 'CMCRMN91S148WCLW'),
(4, 'BRLGXX64E15PF9XC'),
(4, 'TSTTNX52B01461ZW'),
(5, 'FRMSFN51M01FXW6R'),
(5, 'TSXLVC17A28BGCBT'),
(5, 'BSCBCC53D0523W0L'),
(5, 'CVNLMX68P08RWYNW');

--
-- Dumping data for table `prenotazioni`
--

INSERT INTO `prenotazioni` (`id_prenotazione`, `check_in`, `check_out`, `attiva`, `id_hotel`, `numero_camera`, `codice_fiscale_cliente`) VALUES
(1, '2025-07-05', '2025-07-07', 1, 1, 'A100', 'CSRDRN31B15JRLTB'),
(2, '2025-07-22', '2025-07-24', 1, 1, 'B100', 'PRZMLN44T13CNT3Y'),
(3, '2025-07-04', '2025-07-06', 1, 2, 'A150', 'TSXLVC17A28BGCBT'),
(4, '2025-07-11', '2025-07-19', 1, 3, 'C102', 'TRCRFL34M03JV87F'),
(5, '2025-07-29', '2025-07-31', 1, 1, 'B103', 'FRMSFN51M01FXW6R');

--
-- Dumping data for table `ruoli_account`
--

INSERT INTO `ruoli_account` (`id_ruolo`, `nome_ruolo`, `descrizione`) VALUES
(1, 'Amministratore', 'Accesso completo al sistema'),
(2, 'Gestore Hotel', 'Gestione di hotel specifici');

--
-- Dumping data for table `servizi`
--

INSERT INTO `servizi` (`id_servizio`, `nome_servizio`, `categoria_servizio`, `prezzo`) VALUES
(1, 'Colazione in camera', 'Ristorazione', 10),
(2, 'Cambio asciugamani', 'Servizi igiene', 5),
(3, 'Pulizia camera', 'Servizi pulizia', 1),
(4, 'Spa', 'Servizio spa', 30);

--
-- Dumping data for table `servizi_offerti`
--

INSERT INTO `servizi_offerti` (`id_servizio_offerto`, `id_servizio`, `id_hotel`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 1, 2),
(5, 2, 2),
(6, 1, 3),
(7, 2, 3),
(8, 3, 3);

--
-- Dumping data for table `servizi_prenotazioni`
--

INSERT INTO `servizi_prenotazioni` (`id_servizio_prenotazione`, `id_prenotazione`, `id_servizio`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 3),
(5, 3, 2),
(6, 4, 1),
(7, 4, 2),
(8, 4, 2);

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`codice_fiscale`, `nome`, `cognome`, `eta`) VALUES
('BRBMRC04E10F158G', 'Marco', 'Barbera', 21),
('CLSDAESADA43D234', 'Dalila', 'Clemente', 32),
('DSTDNL103AD30FAD', 'Daniele', 'Di Santo', 21),
('MRNSEB04E10F130G', 'Sebastiano', 'Marino', 21),
('NLMRTE02E136F23F', 'Nicola', 'La Torre', 60);

--
-- Dumping data for table `telefono`
--

INSERT INTO `telefono` (`numero`, `descrizione`, `id_hotel`) VALUES
('3203045305', 'Segreteria', 1),
('3353564643', 'Segreteria', 3),
('3435646464', 'Reception', 2),
('3545946940', 'Reception', 1),
('3676868667', 'Prenotazioni', 3),
('4343434342', 'Segreteria', 2);

--
-- Dumping data for table `tipi_pagamento`
--

INSERT INTO `tipi_pagamento` (`id_tipo_pagamento`, `metodo_pagamento`) VALUES
(1, 'carta di credito'),
(2, 'bonifico'),
(3, 'contanti');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
