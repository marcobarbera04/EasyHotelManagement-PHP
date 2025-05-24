-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2025 at 05:39 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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

--
-- Dumping data for table `camere`
--

INSERT INTO `camere` (`numero_camera`, `id_edificio`) VALUES
('A101', 1),
('A102', 1),
('A103', 1),
('B200', 3);

--
-- Dumping data for table `edifici`
--

INSERT INTO `edifici` (`id_edificio`, `nome`, `id_hotel`) VALUES
(1, 'Edificio stanze', 1),
(2, 'Edificio ristorante', 1),
(3, 'Edificio stanze', 2);

--
-- Dumping data for table `email`
--

INSERT INTO `email` (`email`, `descrizione`, `id_hotel`) VALUES
('hoteltop@gmail.com', 'Indirizzo segreteria', 1);

--
-- Dumping data for table `fatture`
--

INSERT INTO `fatture` (`id_fattura`, `data_emissione`, `importo_totale`, `numero_carta`, `cvc_carta`, `numero_conto_corrente`, `id_tipo_pagamento`, `id_prenotazione`) VALUES
(1, '2025-02-06', 500, '6676767676', 555, NULL, 1, 2);

--
-- Dumping data for table `hotel`
--

INSERT INTO `hotel` (`id_hotel`, `nome`, `via`) VALUES
(1, 'Hotel top', 'Via top'),
(2, 'Hotel non top', 'Via non top');

--
-- Dumping data for table `impieghi_hotel`
--

INSERT INTO `impieghi_hotel` (`codice_fiscale`, `id_hotel`) VALUES
('PLNNLC32R25C013S', 2),
('DCFPRF44M28E993N', 1),
('LBVTTG61C70H472H', 1),
('ZXVPWS76B20A192V', 1);

--
-- Dumping data for table `mansioni`
--

INSERT INTO `mansioni` (`mansione`, `descrizione`) VALUES
('Cameriere', 'Cameriere'),
('Inserviente', 'Inserviente');

--
-- Dumping data for table `mansioni_staff`
--

INSERT INTO `mansioni_staff` (`codice_fiscale`, `mansione`) VALUES
('PLNNLC32R25C013S', 'Inserviente'),
('DCFPRF44M28E993N', 'Cameriere'),
('DCFPRF44M28E993N', 'Inserviente');

--
-- Dumping data for table `ospiti`
--

INSERT INTO `ospiti` (`codice_fiscale`, `nome`, `cognome`, `eta`, `telefono`, `indirizzo`) VALUES
('FNNPBT74R15E517C', 'Fentanio', 'Fifio', 32, NULL, 'via plea'),
('NSSLBT79B53G512N', 'Nissan', 'Sio', 24, NULL, 'via gagagagmm'),
('QKFFCM82H60I563A', 'Quecca', 'Pocchia', 43, '3220619460', 'Via placida'),
('RLWXMS65D15F726Y', 'Alberto', 'Sugna', 54, NULL, 'Via viosa'),
('TXCLMC32M18C343K', 'Arancia', 'Trapani', 79, NULL, 'Via pelo'),
('YBVHSL75P12A789M', 'Pesca', 'Ardito', 54, NULL, 'Via via');

--
-- Dumping data for table `prenotazioni`
--

INSERT INTO `prenotazioni` (`id_prenotazione`, `check_in`, `check_out`, `attiva`, `id_hotel`, `numero_camera`, `codice_fiscale_cliente`) VALUES
(2, '2025-02-14', '2025-02-28', 0, 1, 'A101', 'FNNPBT74R15E517C');

--
-- Dumping data for table `servizi`
--

INSERT INTO `servizi` (`id_servizio`, `nome_servizio`, `categoria_servizio`, `prezzo`) VALUES
(1, 'Pulizia camera', 'Pulizia', 0),
(2, 'Servizio in camera', 'Ristorazione', 5);

--
-- Dumping data for table `servizi_offerti`
--

INSERT INTO `servizi_offerti` (`id_servizio`, `id_hotel`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2);

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`codice_fiscale`, `nome`, `cognome`, `eta`) VALUES
('DCFPRF44M28E993N', 'daniele', 'di santo', 20),
('LBVTTG61C70H472H', 'pippo', 'pippo', 31),
('PLNNLC32R25C013S', 'polacco', 'basilio', 20),
('ZXVPWS76B20A192V', 'pluto', 'pluto', 25);

--
-- Dumping data for table `telefono`
--

INSERT INTO `telefono` (`numero`, `descrizione`, `id_hotel`) VALUES
('3319585617', 'Numero top', 1);

--
-- Dumping data for table `tipi_pagamento`
--

INSERT INTO `tipi_pagamento` (`id_tipo_pagamento`, `metodo_pagamento`) VALUES
(1, 'carta'),
(2, 'conto corrente');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
