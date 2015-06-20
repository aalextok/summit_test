--
-- Table structure for table `image`
--

CREATE TABLE IF NOT EXISTS `image` (
  `id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `model` enum('User','Place','Competition','Visit','Participation','Prize','Rank') DEFAULT NULL,
  `model_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `image` ADD `hash` VARCHAR(32) NULL AFTER `location`;

ALTER TABLE `user` ADD `distance` INT NULL AFTER `meters_above_sea_level`;

ALTER TABLE `competition` ADD `meters_above_sea_level` INT NULL AFTER `close_time`, ADD `distance` INT NULL AFTER `meters_above_sea_level`, ADD `points` INT NULL AFTER `distance`, ADD `summits` INT NULL AFTER `points`;

ALTER TABLE `user` CHANGE `rank` `rank` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

--
-- Table structure for table `rank`
--

CREATE TABLE IF NOT EXISTS `rank` (
  `id` int(11) NOT NULL,
  `rank` varchar(255) NOT NULL,
  `points` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rank`
--
ALTER TABLE `rank`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rank` (`rank`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rank`
--
ALTER TABLE `rank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `prize` DROP `summits`, DROP `meters_above_sea_level`, DROP `distance`;
ALTER TABLE `prize` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;