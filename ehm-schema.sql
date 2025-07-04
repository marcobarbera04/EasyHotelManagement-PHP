-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql_ehm
-- Generation Time: Jul 04, 2025 at 04:10 PM
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
-- Database: `ehm`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id_account` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `codice_fiscale` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_ruolo` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `camere`
--

CREATE TABLE `camere` (
  `numero_camera` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `id_edificio` int NOT NULL,
  `posti_letto` int NOT NULL,
  `prezzo_notte` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `edifici`
--

CREATE TABLE `edifici` (
  `id_edificio` int NOT NULL,
  `nome` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `id_hotel` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email`
--

CREATE TABLE `email` (
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `descrizione` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `id_hotel` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fatture`
--

CREATE TABLE `fatture` (
  `id_fattura` int NOT NULL,
  `data_emissione` date NOT NULL,
  `importo_totale` double NOT NULL,
  `numero_carta` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cvc_carta` int DEFAULT NULL,
  `numero_conto_corrente` varchar(12) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_tipo_pagamento` int NOT NULL,
  `id_prenotazione` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotel`
--

CREATE TABLE `hotel` (
  `id_hotel` int NOT NULL,
  `nome` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `via` varchar(128) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotel_gestiti_account`
--

CREATE TABLE `hotel_gestiti_account` (
  `id` int NOT NULL,
  `id_account` int NOT NULL,
  `id_hotel` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `impieghi_hotel`
--

CREATE TABLE `impieghi_hotel` (
  `id_impiego` int NOT NULL,
  `codice_fiscale` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `id_hotel` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mansioni`
--

CREATE TABLE `mansioni` (
  `mansione` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `descrizione` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mansioni_staff`
--

CREATE TABLE `mansioni_staff` (
  `codice_fiscale` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `mansione` varchar(45) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ospiti`
--

CREATE TABLE `ospiti` (
  `codice_fiscale` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `nome` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `cognome` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `eta` int NOT NULL,
  `telefono` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `indirizzo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ospiti_prenotazione`
--

CREATE TABLE `ospiti_prenotazione` (
  `id_prenotazione` int NOT NULL,
  `codice_fiscale` varchar(16) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prenotazioni`
--

CREATE TABLE `prenotazioni` (
  `id_prenotazione` int NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `attiva` tinyint(1) NOT NULL,
  `id_hotel` int NOT NULL,
  `numero_camera` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `codice_fiscale_cliente` varchar(16) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ruoli_account`
--

CREATE TABLE `ruoli_account` (
  `id_ruolo` int NOT NULL,
  `nome_ruolo` varchar(255) NOT NULL,
  `descrizione` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `servizi`
--

CREATE TABLE `servizi` (
  `id_servizio` int NOT NULL,
  `nome_servizio` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `categoria_servizio` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `prezzo` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `servizi_offerti`
--

CREATE TABLE `servizi_offerti` (
  `id_servizio_offerto` int NOT NULL,
  `id_servizio` int NOT NULL,
  `id_hotel` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `servizi_prenotazioni`
--

CREATE TABLE `servizi_prenotazioni` (
  `id_servizio_prenotazione` int NOT NULL,
  `id_prenotazione` int NOT NULL,
  `id_servizio` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `codice_fiscale` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `nome` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `cognome` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `eta` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `telefono`
--

CREATE TABLE `telefono` (
  `numero` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `descrizione` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `id_hotel` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tipi_pagamento`
--

CREATE TABLE `tipi_pagamento` (
  `id_tipo_pagamento` int NOT NULL,
  `metodo_pagamento` varchar(45) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id_account`),
  ADD KEY `codice_fiscale_account` (`codice_fiscale`),
  ADD KEY `id_ruolo` (`id_ruolo`);

--
-- Indexes for table `camere`
--
ALTER TABLE `camere`
  ADD PRIMARY KEY (`numero_camera`),
  ADD KEY `camere_edificio` (`id_edificio`);

--
-- Indexes for table `edifici`
--
ALTER TABLE `edifici`
  ADD PRIMARY KEY (`id_edificio`),
  ADD KEY `edifici_hotel` (`id_hotel`);

--
-- Indexes for table `email`
--
ALTER TABLE `email`
  ADD PRIMARY KEY (`email`),
  ADD KEY `email_hotel` (`id_hotel`);

--
-- Indexes for table `fatture`
--
ALTER TABLE `fatture`
  ADD PRIMARY KEY (`id_fattura`),
  ADD KEY `fatture_tipo_pagamento` (`id_tipo_pagamento`),
  ADD KEY `fatture_prenotazione` (`id_prenotazione`);

--
-- Indexes for table `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`id_hotel`);

--
-- Indexes for table `hotel_gestiti_account`
--
ALTER TABLE `hotel_gestiti_account`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_account` (`id_account`),
  ADD KEY `id_hotel` (`id_hotel`);

--
-- Indexes for table `impieghi_hotel`
--
ALTER TABLE `impieghi_hotel`
  ADD PRIMARY KEY (`id_impiego`),
  ADD KEY `impiego_hotel_staff` (`codice_fiscale`),
  ADD KEY `impiego_hotel_hotel` (`id_hotel`);

--
-- Indexes for table `mansioni`
--
ALTER TABLE `mansioni`
  ADD PRIMARY KEY (`mansione`);

--
-- Indexes for table `mansioni_staff`
--
ALTER TABLE `mansioni_staff`
  ADD KEY `mansione_staff_staff` (`codice_fiscale`),
  ADD KEY `mansione_staff_tipo_mansione` (`mansione`);

--
-- Indexes for table `ospiti`
--
ALTER TABLE `ospiti`
  ADD PRIMARY KEY (`codice_fiscale`);

--
-- Indexes for table `ospiti_prenotazione`
--
ALTER TABLE `ospiti_prenotazione`
  ADD KEY `associazione_ospite_prenotazione` (`id_prenotazione`),
  ADD KEY `associazione_ospite_ospite` (`codice_fiscale`);

--
-- Indexes for table `prenotazioni`
--
ALTER TABLE `prenotazioni`
  ADD PRIMARY KEY (`id_prenotazione`),
  ADD KEY `prenotazioni_hotel` (`id_hotel`),
  ADD KEY `prenotazioni_cliente` (`codice_fiscale_cliente`),
  ADD KEY `prenotazioni_camera` (`numero_camera`);

--
-- Indexes for table `ruoli_account`
--
ALTER TABLE `ruoli_account`
  ADD PRIMARY KEY (`id_ruolo`);

--
-- Indexes for table `servizi`
--
ALTER TABLE `servizi`
  ADD PRIMARY KEY (`id_servizio`);

--
-- Indexes for table `servizi_offerti`
--
ALTER TABLE `servizi_offerti`
  ADD PRIMARY KEY (`id_servizio_offerto`),
  ADD KEY `servizi_offerti_servizio` (`id_servizio`),
  ADD KEY `servizi_offerti_hotel` (`id_hotel`);

--
-- Indexes for table `servizi_prenotazioni`
--
ALTER TABLE `servizi_prenotazioni`
  ADD PRIMARY KEY (`id_servizio_prenotazione`),
  ADD KEY `servizi_prenotazioni_prenotazione` (`id_prenotazione`),
  ADD KEY `servizi_prenotazioni_servizio` (`id_servizio`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`codice_fiscale`);

--
-- Indexes for table `telefono`
--
ALTER TABLE `telefono`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `telefono_hotel` (`id_hotel`);

--
-- Indexes for table `tipi_pagamento`
--
ALTER TABLE `tipi_pagamento`
  ADD PRIMARY KEY (`id_tipo_pagamento`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id_account` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `edifici`
--
ALTER TABLE `edifici`
  MODIFY `id_edificio` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fatture`
--
ALTER TABLE `fatture`
  MODIFY `id_fattura` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hotel`
--
ALTER TABLE `hotel`
  MODIFY `id_hotel` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hotel_gestiti_account`
--
ALTER TABLE `hotel_gestiti_account`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `impieghi_hotel`
--
ALTER TABLE `impieghi_hotel`
  MODIFY `id_impiego` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prenotazioni`
--
ALTER TABLE `prenotazioni`
  MODIFY `id_prenotazione` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ruoli_account`
--
ALTER TABLE `ruoli_account`
  MODIFY `id_ruolo` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `servizi`
--
ALTER TABLE `servizi`
  MODIFY `id_servizio` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `servizi_offerti`
--
ALTER TABLE `servizi_offerti`
  MODIFY `id_servizio_offerto` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `servizi_prenotazioni`
--
ALTER TABLE `servizi_prenotazioni`
  MODIFY `id_servizio_prenotazione` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tipi_pagamento`
--
ALTER TABLE `tipi_pagamento`
  MODIFY `id_tipo_pagamento` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `codice_fiscale_account` FOREIGN KEY (`codice_fiscale`) REFERENCES `staff` (`codice_fiscale`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `id_ruolo` FOREIGN KEY (`id_ruolo`) REFERENCES `ruoli_account` (`id_ruolo`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `camere`
--
ALTER TABLE `camere`
  ADD CONSTRAINT `camere_edificio` FOREIGN KEY (`id_edificio`) REFERENCES `edifici` (`id_edificio`);

--
-- Constraints for table `edifici`
--
ALTER TABLE `edifici`
  ADD CONSTRAINT `edifici_hotel` FOREIGN KEY (`id_hotel`) REFERENCES `hotel` (`id_hotel`);

--
-- Constraints for table `email`
--
ALTER TABLE `email`
  ADD CONSTRAINT `email_hotel` FOREIGN KEY (`id_hotel`) REFERENCES `hotel` (`id_hotel`);

--
-- Constraints for table `fatture`
--
ALTER TABLE `fatture`
  ADD CONSTRAINT `fatture_prenotazione` FOREIGN KEY (`id_prenotazione`) REFERENCES `prenotazioni` (`id_prenotazione`),
  ADD CONSTRAINT `fatture_tipo_pagamento` FOREIGN KEY (`id_tipo_pagamento`) REFERENCES `tipi_pagamento` (`id_tipo_pagamento`);

--
-- Constraints for table `hotel_gestiti_account`
--
ALTER TABLE `hotel_gestiti_account`
  ADD CONSTRAINT `id_account` FOREIGN KEY (`id_account`) REFERENCES `accounts` (`id_account`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `id_hotel` FOREIGN KEY (`id_hotel`) REFERENCES `hotel` (`id_hotel`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `impieghi_hotel`
--
ALTER TABLE `impieghi_hotel`
  ADD CONSTRAINT `impiego_hotel_hotel` FOREIGN KEY (`id_hotel`) REFERENCES `hotel` (`id_hotel`),
  ADD CONSTRAINT `impiego_hotel_staff` FOREIGN KEY (`codice_fiscale`) REFERENCES `staff` (`codice_fiscale`);

--
-- Constraints for table `mansioni_staff`
--
ALTER TABLE `mansioni_staff`
  ADD CONSTRAINT `mansione_staff_staff` FOREIGN KEY (`codice_fiscale`) REFERENCES `staff` (`codice_fiscale`),
  ADD CONSTRAINT `mansione_staff_tipo_mansione` FOREIGN KEY (`mansione`) REFERENCES `mansioni` (`mansione`);

--
-- Constraints for table `ospiti_prenotazione`
--
ALTER TABLE `ospiti_prenotazione`
  ADD CONSTRAINT `associazione_ospite_ospite` FOREIGN KEY (`codice_fiscale`) REFERENCES `ospiti` (`codice_fiscale`),
  ADD CONSTRAINT `associazione_ospite_prenotazione` FOREIGN KEY (`id_prenotazione`) REFERENCES `prenotazioni` (`id_prenotazione`);

--
-- Constraints for table `prenotazioni`
--
ALTER TABLE `prenotazioni`
  ADD CONSTRAINT `prenotazioni_camera` FOREIGN KEY (`numero_camera`) REFERENCES `camere` (`numero_camera`),
  ADD CONSTRAINT `prenotazioni_cliente` FOREIGN KEY (`codice_fiscale_cliente`) REFERENCES `ospiti` (`codice_fiscale`),
  ADD CONSTRAINT `prenotazioni_hotel` FOREIGN KEY (`id_hotel`) REFERENCES `hotel` (`id_hotel`);

--
-- Constraints for table `servizi_offerti`
--
ALTER TABLE `servizi_offerti`
  ADD CONSTRAINT `servizi_offerti_hotel` FOREIGN KEY (`id_hotel`) REFERENCES `hotel` (`id_hotel`),
  ADD CONSTRAINT `servizi_offerti_servizio` FOREIGN KEY (`id_servizio`) REFERENCES `servizi` (`id_servizio`);

--
-- Constraints for table `servizi_prenotazioni`
--
ALTER TABLE `servizi_prenotazioni`
  ADD CONSTRAINT `servizi_prenotazioni_prenotazione` FOREIGN KEY (`id_prenotazione`) REFERENCES `prenotazioni` (`id_prenotazione`),
  ADD CONSTRAINT `servizi_prenotazioni_servizio` FOREIGN KEY (`id_servizio`) REFERENCES `servizi` (`id_servizio`);

--
-- Constraints for table `telefono`
--
ALTER TABLE `telefono`
  ADD CONSTRAINT `telefono_hotel` FOREIGN KEY (`id_hotel`) REFERENCES `hotel` (`id_hotel`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
