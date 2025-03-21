-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 21 2025 г., 00:13
-- Версия сервера: 8.0.30
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `DigiWave`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cart`
--

CREATE TABLE `cart` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `pid` int NOT NULL,
  `qty` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cart`
--

INSERT INTO `cart` (`id`, `uid`, `pid`, `qty`) VALUES
(3, 3, 3, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `product_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `product_type`) VALUES
(1, 'Видеокарта'),
(2, 'Корпус'),
(3, 'Мышь');

-- --------------------------------------------------------

--
-- Структура таблицы `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_11_16_081759_categories', 1),
(6, '2024_11_16_081943_products', 1),
(7, '2024_11_26_064226_cart', 1),
(8, '2024_11_29_103043_orders', 1),
(9, '2024_12_02_090443_add_admin', 1),
(10, '2025_01_19_103020_add_description_to_products_table', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` int NOT NULL,
  `pid` int NOT NULL,
  `qty` int NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Новый',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `number` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `uid`, `pid`, `qty`, `status`, `created_at`, `number`) VALUES
(8, 2, 24, 2, 'Отменен', '2025-01-26 09:55:08', 'so8PDjCm'),
(9, 2, 6, 3, 'Отменен', '2025-01-26 09:55:08', 'so8PDjCm'),
(10, 2, 8, 2, 'Отменен', '2025-01-26 09:55:08', 'so8PDjCm'),
(11, 2, 26, 2, 'Отменен', '2025-01-26 09:55:08', 'so8PDjCm'),
(12, 6, 27, 2, 'Подтвержден', '2025-01-26 14:14:02', 'GGjC73in'),
(13, 6, 2, 5, 'Подтвержден', '2025-01-26 14:14:02', 'GGjC73in'),
(14, 6, 5, 1, 'Подтвержден', '2025-01-26 14:14:02', 'GGjC73in'),
(15, 6, 11, 1, 'Подтвержден', '2025-01-26 14:14:02', 'GGjC73in'),
(16, 7, 26, 1, 'Подтвержден', '2025-02-04 04:59:18', 'ilF3ryZB'),
(17, 7, 1, 1, 'Подтвержден', '2025-02-04 04:59:18', 'ilF3ryZB'),
(18, 8, 10, 1, 'Новый', '2025-03-03 13:36:06', 'ZGQOS5ns'),
(19, 8, 6, 2, 'Новый', '2025-03-03 13:36:06', 'ZGQOS5ns'),
(20, 8, 20, 3, 'Новый', '2025-03-03 13:36:06', 'ZGQOS5ns');

-- --------------------------------------------------------

--
-- Структура таблицы `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_type` int UNSIGNED NOT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `title`, `price`, `img`, `product_type`, `country`, `color`, `qty`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Видеокарта KFA2 GeForce RTX 4060 CORE Black', 37900, 'rtx4060.svg', 1, 'Китай', 'Черный', 15, 'Видеокарта KFA2 GeForce RTX 4060 CORE Black [46NSL8MD9NXK] подходит для сборки и модернизации игровых системных блоков среднего уровня. Графический процессор устройства – GeForce RTX 4060. Он базируется на микроархитектуре NVIDIA Ada Lovelace и произведен по техпроцессу 5 нм. Штатная частота видеочипа равна 1830 МГц, а турбочастота – 2490 МГц.', '2025-01-20 09:23:24', '2025-01-20 09:23:24'),
(2, 'Видеокарта MSI GeForce RTX 4060 VENTUS 2X WHITE OC', 40999, 'MSI GeForce RTX 4060 VENTUS 2X WHITE OC.svg', 1, 'Тайвань', 'Белый', 5, 'Видеокарта MSI GeForce RTX 4060 VENTUS 2X WHITE OC [GeForce RTX 4060 VENTUS 2X WHITE 8G OC] – оснащение для игровых системных блоков. Благодаря длине 199 мм видеоадаптер совместим с компактными корпусами. Устройство занимает 2 слота расширения и рассчитано на работу в сингл-режиме. ', '2025-01-20 09:30:03', '2025-01-20 09:30:03'),
(3, 'Видеокарта PowerColor AMD Radeon RX 6600 Fighter', 27999, 'PowerColor AMD Radeon RX 6600 Fighter.svg', 1, 'Тайвань', 'Черный', 50, 'Видеокарта PowerColor AMD Radeon RX 6600 Fighter разработана для достижения качественного геймплея и плавного изображения при игре с разрешением до 1440p. Процессор на архитектуре RDNA 2 функционирует с частотой 1626 МГц, способной увеличиваться в режиме вычислительного разгона в пределах показателя 2491 МГц. Характеристики дополнены 8 ГБ памяти стандарта GDDR6.', '2025-01-20 09:32:45', '2025-01-20 09:32:45'),
(4, 'Видеокарта GIGABYTE GeForce RTX 4060 EAGLE OC ICE', 42999, 'GIGABYTE GeForce RTX 4060 EAGLE OC ICE.svg', 1, 'Китай', 'Белый', 4, 'Видеокарта GIGABYTE GeForce RTX 4060 EAGLE OC ICE [GV-N4060EAGLEOC ICE-8GD] представлена в белом цвете. Микроархитектура Ada Lovelace с поддержкой технологий NVIDIA DLSS 3 и трассировки лучей обеспечивает точную прорисовку деталей, минимальные задержки и реалистичность графики. С помощью программы CONTROL CENTER выполняется мониторинг и управление параметрами производительности.', '2025-01-20 09:35:03', '2025-01-20 09:35:03'),
(5, 'Видеокарта KFA2 GeForce RTX 3050 X Black [35NSL8MD6YEK]', 27000, 'KFA2 GeForce RTX 3050 X Black.svg', 1, 'Китай', 'Черный', 12, 'Видеокарта KFA2 GeForce RTX 3050 X BLACK расширяет возможности игровых систем. Она поддерживает штатную частоту работы 1552 МГц. Повышение частоты в турборежиме до 1837 МГц обеспечивает прирост производительности, даже при работе с мощными графическими приложениями и играми. Видеопамять GDDR6 объемом 8 ГБ при сниженном энергопотреблении позволяет системе работать быстрее до двух раз.', '2025-01-20 09:38:47', '2025-01-20 09:38:47'),
(6, 'Видеокарта GIGABYTE GeForce RTX 4080 SUPER GAMING OC', 139999, 'GIGABYTE GeForce RTX 4080 SUPER GAMING OC.svg', 1, 'Китай', 'Черный', 15, 'Видеокарта GIGABYTE GeForce RTX 4080 SUPER GAMING OC [GV-N408SGAMING OC-16GD] используется при комплектации мощных игровых компьютеров. Модель оснащена графическим процессором GeForce RTX 4080 SUPER на базе микроархитектуры NVIDIA Ada Lovelace. Его потенциал раскрывает 16-гигабайтная память GDDR6X с пропускной способностью 736 ГБ/с и эффективной частотой 23000 МГц. Быстродействие видеоадаптера достаточно для использования почти любых видеоигр на максимальных настройках графики.', '2025-01-20 09:42:10', '2025-01-20 09:42:10'),
(7, 'Видеокарта Sapphire AMD Radeon RX 7700 XT PULSE', 59999, 'Sapphire AMD Radeon RX 7700 XT PULSE.svg', 1, 'Китай', 'Черный', 8, 'Видеокарта Sapphire AMD Radeon RX 7700 XT PULSE [11335-04] – оснащение для игрового компьютера. Производительность устройства позволяет использовать большинство видеоигр на средних или высоких настройках графики. Быстродействие видеоадаптера обеспечивают графический процессор Radeon RX 7700 XT и 12 ГБ памяти GDDR6 с пропускной способностью 432 ГБ/с. Аппаратное ускорение трассировки лучей производят 54 ядра RT. 3456 универсальных процессоров поддерживают высокую частоту кадров.', '2025-01-20 09:45:28', '2025-01-20 09:45:28'),
(8, 'Видеокарта ASRock AMD Radeon RX 6750 GRE Challenger OC', 39999, 'ASRock AMD Radeon RX 6750 GRE Challenger OC.svg', 1, 'Китай', 'Черный', 31, 'Видеокарта ASRock AMD Radeon RX 6750 GRE Challenger OC [RX6750GRE CL 10GO] рассчитана на оснащение игровых компьютеров. Производительность видеоадаптера достаточна для использования большинства игр на средних или высоких графических настройках. Устройство охлаждается парой 11-лопастных вентиляторов, которые останавливаются при минимальной нагрузке.', '2025-01-20 09:49:04', '2025-01-20 09:49:04'),
(9, 'Видеокарта MSI GeForce GT 710 Silent LP', 4600, 'MSI GeForce GT 710 Silent LP.svg', 1, 'Китай', 'Черный', 50, 'Видеокарта MSI GeForce GT 710 Silent LP [GT 710 2GD3H LP] выпускается под известным брендом. Она подойдет для установки в различные системы, позволит запускать игры и работать с программами, нагружающими графические адаптеры в компьютере.', '2025-01-20 09:51:45', '2025-01-20 09:51:45'),
(10, 'Корпус ARDOR GAMING Rare M2 ', 5999, 'ARDOR GAMING Rare M2 ARGB.svg', 2, 'Китай', 'Черный', 33, 'Черный корпус ARDOR GAMING Rare M2 ARGB для игрового ПК обладает типоразмером Mid-Tower. Конструкция оснащена металлической решеткой на фронтальной панели и стеклянной боковой стенкой. Такой дизайн подчеркивает ARGB-подсветку 4 установленных вентиляторов и позволяет настраивать систему жидкостного охлаждения.', '2025-01-20 10:07:16', '2025-01-20 10:07:16'),
(11, 'Корпус Cougar Duoface Pro RGB', 9999, 'Cougar Duoface Pro RGB.svg', 2, 'Китай', 'Черный', 11, 'Черный корпус Cougar Duoface Pro RGB [CGR-5AD1B-RGB] – высококачественная конфигурация с красивым и стильным дизайном. Он привлекает внимание своей сбалансированностью и комфортом использования. Он имеет инновационную конструкцию, позволяющую изменять в одно касание внешний вид.', '2025-01-20 10:10:23', '2025-01-20 10:10:23'),
(12, 'Корпус ZALMAN N4 Rev.1', 5499, 'ZALMAN N4 Rev.1.svg', 2, 'Китай', 'Черный', 15, 'Корпус ZALMAN N4 Rev.1 черного цвета относится к форм-фактору Mid-Tower. Его конфигурация позволяет собрать внутри производительную «машину». Пластик, сталь и закаленное стекло лежат в основе прочности конструкции. FRGB-подсветка дополняет ее стильный дизайн. Управлять ей можно при помощи кнопки снаружи.', '2025-01-20 10:11:36', '2025-01-20 10:11:36'),
(13, 'Корпус DEEPCOOL CC560 WH V2', 6300, 'DEEPCOOL CC560 WH V2.svg', 2, 'Китай', 'Белый', 12, 'Корпус DEEPCOOL CC560 WH V2 [R-CC560-WHGAA4-G-2] белого цвета с полноразмерным боковым окном из закаленного стекла подходит для создания мощной игровой системы или производительного универсального компьютера для дома или офиса. 4 вентилятора типоразмера 120 мм с голубой подсветкой гарантируют высокую эффективность охлаждения внутреннего пространства системного блока. ', '2025-01-20 10:17:03', '2025-01-20 10:17:03'),
(14, 'Корпус DEXP DC-201M', 1700, 'DEXP DC-201M.svg', 2, 'Китай', 'Черный', 22, 'Корпус DEXP DC-201M имеет классический внешний вид. Эргономика модели на высоте: кнопки управления, а также наиболее часто используемые порты и разъемы размещены в наиболее доступном месте, возле стыка передней панели и верхней части корпуса. Типоразмер корпуса – Mini-Tower. Модель подойдет для сборки компактных компьютеров офисного класса. ', '2025-01-20 10:19:36', '2025-01-20 10:19:36'),
(15, 'Корпус ARDOR GAMING Rare Minicase ', 44000, 'ARDOR GAMING Rare Minicase MS3 WG.svg', 2, 'Китай', 'Белый', 4, 'Корпус ARDOR GAMING Rare Minicase MS3 WG – это мини-конфигурация для игровых ПК. Он имеет подходит для материнских плат форматов Mini-ITX и Micro-ATX. Корпус выполнен в черном цвете с окантовкой черного цвета. Он обладает высокой прочностью и защищен от вибраций и перепадов температур. ', '2025-01-20 10:20:59', '2025-01-20 10:20:59'),
(16, 'Корпус Cougar MX330-F', 5699, 'Cougar MX600 RGB.svg', 2, 'Китай', 'Черный', 5, 'Корпус Cougar MX330-F, боковая панель которого выполнена из оргстекла, поражает своим внешним видом. Все компоненты вашей системы будут как на ладони: вы сможете любоваться ими постоянно. Завершает эффектную картину подсветка красного цвета.', '2025-01-24 11:15:05', '2025-01-24 11:15:05'),
(17, 'Корпус Cougar MX600 RGB White', 11400, 'MX330.svg', 2, 'Китай', 'Белый', 5, 'Корпус Cougar MX600 RGB White представлен в типоразмере Full-Tower, который позволит разместить в нем материнскую плату разного форм-фактора, видеокарту длиной до 400 мм и процессорный кулер высотой до 180 мм. Модель белого цвета предусматривает прозрачное окно из закаленного стекла на боковой стенке. Через него можно увидеть всю компьютерную сборку с включенной подсветкой комплектующих. ', '2025-01-24 11:22:32', '2025-01-24 11:22:32'),
(18, 'Корпус Cougar MX220 RGB', 6000, 'Cougar MX220 RGB.svg', 2, 'Китай', 'Черный', 21, 'Корпус Cougar MX220 RGB представлен в типоразмере Mid Tower и обладает габаритами 205 x 485 x 393 мм. В нем можно разместить все необходимые комплектующие и обеспечить при этом нормальную циркуляцию потоков воздуха для охлаждения «горячих» компонентов сборки. Материнская плата располагается в корпусе вертикально, блок питания длиной до 160 мм размещается снизу. При сборке ПК в представленной модели можно задействовать видеокарту длиной до 340 мм и процессорный кулер высотой до 160 мм.', '2025-01-24 11:47:42', '2025-01-24 11:47:42'),
(19, 'Мышь проводная ARDOR GAMING Fury', 1700, 'ARDOR GAMING Fury.svg', 3, 'Китай', 'Черный', 11, 'Мышь проводная ARDOR GAMING Fury [ARD-FURY3327-BK] собрана в эффектно выглядящем эргономичном корпусе, рассчитанном на хват правой рукой. Манипулятор украшен эффектной разноцветной подсветкой. Мышь ориентирована на совместное использование с игровыми стационарными компьютерами и ноутбуками. Модель оснащена оптическим светодиодным датчиком PixArt PMW3327, обеспечивающим разрешение до 12400 dpi.', '2025-01-24 11:50:49', '2025-01-24 11:50:49'),
(20, 'Мышь беспроводная Logitech G PRO X SUPERLIGHT 2', 16599, 'Мышь беспроводная Logitech G PRO X SUPERLIGHT 2.svg', 3, 'Китай', 'Черный', 14, 'Мышь беспроводная Logitech G PRO X SUPERLIGHT 2 стала обладателем оптического светодиодного сенсора HERO 2, который имеет настраиваемое разрешение в диапазоне от 100 до 32000 dpi. Вы сможете выбрать подходящий режим работы в зависимости от решаемых задач, типа монитора и поверхности под манипулятором. В конструкцию устройства входят пять кнопок с возможностью их программирования на выполнение определенных действий.', '2025-01-24 11:53:36', '2025-01-24 11:53:36'),
(21, 'Мышь проводная Logitech G102 LIGHTSYNC', 2300, 'Мышь проводная Logitech G102 LIGHTSYNC.svg', 3, 'Китай', 'Черный', 121, 'Мышь Logitech G102 LIGHTSYNC позволяет геймеру проходить игры с максимальным комфортом и эффективностью. Данная модель манипулятора обеспечивает стабильное подключение по проводному интерфейсу USB и быструю реакцию на перемещения курсора благодаря датчику с показателем чувствительности до 8000 dpi. Мышь оборудована 6 кнопками.\r\nОсновные кнопки основаны на металлическом пружинном механизме для точного срабатывания.', '2025-01-24 11:56:59', '2025-01-24 11:56:59'),
(22, 'Мышь беспроводная Logitech G304 LIGHTSPEED', 3999, 'Мышь беспроводная Logitech G304 LIGHTSPEED.svg', 3, 'Китай', 'Черный', 38, 'Оснащенная 6 программируемыми кнопками мышь беспроводная Logitech G304 LIGHTSPEED [910-005286] подходит для комплектации ноутбуков и стационарных компьютеров, преимущественно используемых для игр. Манипулятор оборудован оптическим светодиодным сенсором HERO, демонстрирующим значительное (до 12000 dpi) разрешение. Высокая точность позиционирования курсора помогает достигать успеха в динамичных видеоиграх. В этой связи заслуживает внимания и показатель максимально допустимого ускорения, равный 40 G.', '2025-01-24 11:59:34', '2025-01-24 11:59:34'),
(23, 'Мышь беспроводная/проводная LAMZU Maya', 8120, 'Мышь беспроводнаяпроводная LAMZU Maya.svg', 3, 'Китай', 'Белый', 63, 'Мышь беспроводная/проводная LAMZU Maya белого цвета может подключаться к компьютеру при помощи радиоканала с дальностью действия до 10 м или 1.8-метрового кабеля в тканевой оплетке для дополнительной защиты от истирания. В конструкции модели есть пять кнопок с поддержкой программирования на выполнение определенной последовательности действий, что пригодится при повседневной работе за компьютером или при прохождении игр.', '2025-01-24 12:01:25', '2025-01-24 12:01:25'),
(24, 'Мышь беспроводная Razer Basilisk V3 Hyperspeed', 5800, 'Мышь беспроводная Razer Basilisk V3 Hyperspeed.svg', 3, 'Китай', 'Черный', 16, 'Мышь беспроводная Razer Basilisk V3 Hyperspeed оснащена 7 программируемыми кнопками, которые позволят выбрать свой собственный стиль игры. Удобная форма корпуса поддерживает различные типы хвата и обеспечивает многочасовое прохождение интересующих игровых проектов. Многофункциональный триггер позволит наслаждаться многообразием комбинаций макросов и команд, настраиваемых быстро и просто.', '2025-01-24 12:03:37', '2025-01-24 12:03:37'),
(25, 'Мышь беспроводная/проводная ARDOR GAMING Phantom PRO V2', 5400, 'Мышь беспроводнаяпроводная ARDOR GAMING Phantom PRO V2.svg', 3, 'Китай', 'Белый', 44, 'Мышь беспроводная/проводная ARDOR GAMING Phantom PRO V2 в белом корпусе с сотами подходит для хвата любой рукой. 7 режимов чувствительности сохраняют точность управления в разных играх. 6 кнопок с поддержкой программирования позволяют настраивать быстрый доступ к нужным функциям.\r\nБлагодаря встроенной памяти ARDOR GAMING Phantom PRO V2 сохраняет заданные параметры после отключения от ПК. Вы можете отсоединять кабель для беспроводной работы мыши по радиоканалу на расстоянии 10 м.', '2025-01-24 12:06:47', '2025-01-24 12:06:47'),
(26, 'Мышь беспроводная LAMZU & FNATIC Thorn', 8099, 'Мышь беспроводная LAMZU & FNATIC Thorn.svg', 3, 'Китай', 'Оранжевый', 5, 'Мышь беспроводная/проводная LAMZU & FNATIC Thorn заключена в корпус из матового пластика, устойчивого к оседанию пыли и накапливанию загрязнений. Форма манипулятора удобна для захвата правой ладонью. Конфигурацией предусмотрено 6 кнопок, в том числе для регулировки разрешения датчика. Кабель длиной 1.8 м защищен тканевой оплеткой, не допускающей образования заломов.\r\nАвтономная работа мыши беспроводной/проводной LAMZU & FNATIC Thorn на протяжении 80 ч обеспечивается аккумулятором. ', '2025-01-24 12:09:44', '2025-01-24 12:09:44'),
(27, 'Мышь проводная Razer DeathAdder V2', 2000, 'Мышь проводная Razer DeathAdder V2.svg', 3, 'Китай', 'Черный', 70, 'Мышь проводная Razer DeathAdder V2 в изящном корпусе с плавными линиями является серьезным геймерским «инструментом». Благодаря разрешению оптического сенсора Razer Focus+, достигающему 20000 dpi, модель обеспечивает максимальную точность позиционирования курсора на экране. Устройство имеет 8 программируемых кнопок, ресурс каждой из которых достигает 70 млн нажатий! Этот беспрецедентный показатель сочетается с наличием интуитивного тактильного колесика прокрутки.', '2025-01-24 12:13:17', '2025-01-24 12:13:17');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `patronymic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `patronymic`, `login`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `is_admin`) VALUES
(9, 'Вадим', 'Грибанов', 'Владимирович', 'mrplayer382', 'hot.ershik@gmail.com', NULL, '$2y$10$83gGnij0SKmmXYWR7qnWfOIQ9jIb4tzvaMINdYtSg/1P3YmrGeTyu', NULL, '2025-03-20 15:20:44', '2025-03-20 15:20:44', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Индексы таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_product_type_foreign` (`product_type`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_login_unique` (`login`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cart`
--
ALTER TABLE `cart`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_product_type_foreign` FOREIGN KEY (`product_type`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
